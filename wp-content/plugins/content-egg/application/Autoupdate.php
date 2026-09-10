<?php

namespace ContentEgg\application;

defined('\ABSPATH') || exit;

use ContentEgg\application\licensing\LicenseClient;
use ContentEgg\application\licensing\LicenseGate;

/**
 * Self-hosted plugin updates via the licensing API.
 *
 * NOTE: the existence of this class is also used by Plugin::isPro() /
 * Plugin::isPaidBuild() to detect the PRO build, so the class name and
 * namespace must not change.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class Autoupdate
{
    const CACHE_TTL = 300; // 5 minutes

    // Marks update/no_update entries this class authored, so the read filter can
    // tell our genuine Pro release apart from a same-slug wordpress.org entry.
    const SELF_FLAG = 'cegg_self';

    private $client;
    private $gate;
    private $plugin_file; // e.g. content-egg/content-egg.php
    private $plugin_slug; // e.g. content-egg

    /**
     * @param bool $offer_updates Whether to advertise Pro updates from the
     *   licensing server. The wordpress.org slug-collision defense is ALWAYS
     *   registered regardless of this flag.
     */
    public function __construct($client = null, $gate = null, $offer_updates = true)
    {
        $this->client = $client ?: new LicenseClient();
        $this->gate   = $gate ?: new LicenseGate($this->client);
        $this->plugin_file = \plugin_basename(\ContentEgg\PLUGIN_FILE);
        $parts = explode('/', $this->plugin_file);
        $this->plugin_slug = $parts[0] ?? Plugin::slug;

        // wordpress.org slug-collision defense — ALWAYS on for the Pro build.
        // Content Egg Pro shares the "content-egg" slug with the free plugin on
        // wordpress.org, so without this the directory's free package can be
        // offered/installed over Pro whenever our updater goes quiet (no token,
        // blocked license, or a stale cached update_plugins transient).
        \add_filter('http_request_args', array($this, 'excludeFromWpOrgCheck'), 10, 2);
        \add_filter('site_transient_update_plugins', array($this, 'sanitizeTransient'));

        // Advertising genuine Pro updates needs a usable token + unblocked license.
        if (!$offer_updates)
        {
            return;
        }
        \add_filter('pre_set_site_transient_update_plugins', array($this, 'checkUpdate'));
        \add_filter('plugins_api', array($this, 'getRemoteInfo'), 10, 3);
    }

    /**
     * http_request_args filter: drop our plugin from the wordpress.org bulk
     * update-check POST so the directory never returns data for the shared
     * "content-egg" slug. Runs for every Pro install, regardless of licensing.
     */
    public function excludeFromWpOrgCheck($args, $url)
    {
        if (!is_string($url) || strpos($url, '//api.wordpress.org/plugins/update-check') === false)
        {
            return $args;
        }
        if (!is_array($args) || empty($args['body']['plugins']) || !is_string($args['body']['plugins']))
        {
            return $args;
        }
        $payload = json_decode($args['body']['plugins'], true);
        if (!is_array($payload) || !isset($payload['plugins'][$this->plugin_file]))
        {
            return $args;
        }
        unset($payload['plugins'][$this->plugin_file]);
        if (!empty($payload['active']) && is_array($payload['active']))
        {
            $payload['active'] = array_values(array_filter(
                $payload['active'],
                function ($p) { return $p !== $this->plugin_file; }
            ));
        }
        $args['body']['plugins'] = \wp_json_encode($payload);
        return $args;
    }

    /**
     * site_transient_update_plugins (read) filter: strip any response/no_update
     * entry for our slug that we did not author — i.e. a wordpress.org free entry
     * baked into a cached transient. Our own entry carries SELF_FLAG and is kept;
     * checkUpdate() re-injects the genuine one on the next refresh.
     */
    public function sanitizeTransient($transient)
    {
        if (!is_object($transient))
        {
            return $transient;
        }
        foreach (array('response', 'no_update') as $bucket)
        {
            if (isset($transient->{$bucket}[$this->plugin_file])
                && !$this->isSelfEntry($transient->{$bucket}[$this->plugin_file]))
            {
                unset($transient->{$bucket}[$this->plugin_file]);
            }
        }
        return $transient;
    }

    private function isSelfEntry($entry)
    {
        if (is_object($entry))
        {
            return !empty($entry->{self::SELF_FLAG});
        }
        if (is_array($entry))
        {
            return !empty($entry[self::SELF_FLAG]);
        }
        return false;
    }

    public function checkUpdate($transient)
    {
        if (empty($transient->checked))
        {
            return $transient;
        }

        // Keeps the "View details" link + auto-update toggle visible when up to date.
        $transient->no_update[$this->plugin_file] = $this->buildNoUpdate();

        // Installed version per WordPress's on-disk plugin scan, not the in-memory
        // version constant: right after a self-update the old class is still loaded,
        // so the constant lags and WP would re-offer the just-installed version
        // until its ~12h update_plugins throttle lapses (phantom same-version row).
        $installed = isset($transient->checked[$this->plugin_file])
            ? (string) $transient->checked[$this->plugin_file]
            : Plugin::version();

        $token = $this->gate->getFreshToken();
        if ($token === null)
        {
            // No usable token: never leave a foreign (wordpress.org) entry that
            // would let the free plugin overwrite Pro.
            if (isset($transient->response[$this->plugin_file])
                && !$this->isSelfEntry($transient->response[$this->plugin_file]))
            {
                unset($transient->response[$this->plugin_file]);
            }
            return $transient;
        }

        $data = $this->fetchUpdate($token, $installed);

        $new_version = is_array($data) ? (string) ($data['new_version'] ?? '') : '';
        $package = is_array($data) ? (string) ($data['package'] ?? ($data['download_url'] ?? '')) : '';
        if (!is_array($data)
            || empty($data['update'])
            || $new_version === ''
            || strpos($package, 'https://') !== 0
            || version_compare($new_version, $installed, '<='))
        {
            // No genuine update: clear any response entry WP carried over so it
            // doesn't render a phantom same-version update row.
            unset($transient->response[$this->plugin_file]);
            return $transient;
        }

        $res = new \stdClass();
        $res->slug = $this->plugin_slug;
        $res->plugin = $this->plugin_file;
        $res->new_version = $new_version;
        $res->url = (string) ($data['homepage'] ?? '');
        $res->package = $package;
        $res->tested = (string) ($data['tested'] ?? '');
        $res->requires = (string) ($data['requires'] ?? '');
        $res->requires_php = (string) ($data['requires_php'] ?? '');
        $res->icons = $this->icons();
        $res->{self::SELF_FLAG} = true;
        unset($transient->no_update[$this->plugin_file]);
        $transient->response[$this->plugin_file] = $res;

        return $transient;
    }

    public function getRemoteInfo($result, $action, $args)
    {
        if ($action !== 'plugin_information')
        {
            return $result;
        }
        if (!is_object($args) || !isset($args->slug) || $args->slug !== $this->plugin_slug)
        {
            return $result;
        }

        $token = $this->gate->getFreshToken();
        if ($token === null)
        {
            return $result;
        }

        $data = $this->fetchUpdate($token);
        if (!$data)
        {
            return $result;
        }

        $package = (string) ($data['package'] ?? ($data['download_url'] ?? ''));
        $plugin_data = \get_plugin_data(\ContentEgg\PLUGIN_FILE, false, false);

        $info = new \stdClass();
        $info->name = $plugin_data['Name'];
        $info->slug = $this->plugin_slug;
        $info->version = (string) ($data['new_version'] ?? Plugin::version());
        $info->author = '<a href="https://www.keywordrush.com">keywordrush.com</a>';
        $info->homepage = (string) ($data['homepage'] ?? '');
        $info->requires = (string) ($data['requires'] ?? '');
        $info->requires_php = (string) ($data['requires_php'] ?? '');
        $info->tested = (string) ($data['tested'] ?? '');
        $info->last_updated = (string) ($data['release_date'] ?? '');
        $info->download_link = $package;
        $info->trunk = $package;
        $info->icons = $this->icons();
        $info->sections = array(
            'description' => sprintf(__('Latest release of the %s plugin.', 'content-egg'), $plugin_data['Name']) . ' ' .
                sprintf(
                    __('Please <a target="_blank" href="%s">find here</a> the release notes.', 'content-egg'),
                    'https://www.keywordrush.com/changelog/content-egg/readme.txt'
                ),
        );

        return $info;
    }

    /** Placeholder entry so WP keeps the View-details link + auto-update toggle when up to date. */
    private function buildNoUpdate()
    {
        $obj = new \stdClass();
        $obj->id = $this->plugin_slug;
        $obj->slug = $this->plugin_slug;
        $obj->plugin = $this->plugin_file;
        $obj->new_version = Plugin::version();
        $obj->url = '';
        $obj->package = '';
        $obj->tested = '';
        $obj->requires = '';
        $obj->requires_php = '';
        $obj->icons = $this->icons();
        $obj->banners = array();
        $obj->banners_rtl = array();
        $obj->{self::SELF_FLAG} = true;
        return $obj;
    }

    /** Plugin icons for the update/info UI. */
    private function icons()
    {
        $svg = \ContentEgg\PLUGIN_RES . '/img/logo.svg';
        return array('svg' => $svg, '1x' => $svg, '2x' => $svg, 'default' => $svg);
    }

    /**
     * GET /licensing/api/update with a 5-minute site-transient cache.
     * @return array|null the `data` block, or null on failure.
     */
    private function fetchUpdate($token, $current = null)
    {
        if ($current === null)
        {
            $current = Plugin::version();
        }
        $product = $this->gate->productCode();
        $cache_key = 'cegg_upd_' . md5($this->plugin_file . '|' . $product . '|' . $current . '|' . substr(hash('sha256', $token), 0, 12));

        $cached = \get_site_transient($cache_key);
        if (is_array($cached))
        {
            return $cached;
        }

        $resp = $this->client->get(
            '/licensing/api/update?product_code=' . rawurlencode($product) . '&current_version=' . rawurlencode($current),
            array('Authorization' => 'Bearer ' . $token)
        );

        if (empty($resp['ok']) || !is_array($resp['data'] ?? null))
        {
            return null;
        }

        \set_site_transient($cache_key, $resp['data'], self::CACHE_TTL);
        return $resp['data'];
    }
}
