<?php

namespace ContentEgg\application\admin;

use ContentEgg\application\Plugin;
use ContentEgg\application\admin\LicConfig;
use ContentEgg\application\components\DurableTransient;
use ContentEgg\application\licensing\LicenseGate;
use ContentEgg\application\licensing\LicenseUtils;

defined('\ABSPATH') || defined('ContentEgg\application\licensing\TESTING') || exit;

/**
 * License status panel + admin notices. Reads cached LicenseGate state.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class LicenseNotices
{
    private static $instance = null;
    private $gate;

    const HIDE_TTL = array(
        'lic_expiring'  => 604800,   // 7d
        'lic_expired'   => 7776000,  // 90d
        'lic_suspended' => 604800,
        'lic_revoked'   => 604800,
        'lic_mismatch'  => 604800,
    );

    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->gate = new LicenseGate();
    }

    public function register()
    {
        \add_action('admin_init', array($this, 'handleActions'));
        \add_action('admin_notices', array($this, 'renderGlobalNotices'));
    }

    private function state(): array
    {
        $cached = $this->gate->getCachedStatus();
        $blocked = $this->gate->isBlocked();
        $graceEndsTs = $this->gate->graceEndsAt();
        return self::computeState(
            is_array($cached) ? $cached : array(),
            LicenseUtils::domain(),
            $this->gate->isLocked(),
            $this->gate->getLockReason(),
            time(),
            $blocked,
            $graceEndsTs
        );
    }

    private static function ctaUrl(string $base, string $campaign, string $content = ''): string
    {
        $args = array('utm_source' => 'cegg', 'utm_medium' => 'plugin', 'utm_campaign' => $campaign);
        if ($content !== '')
        {
            $args['utm_content'] = $content;
        }
        return \esc_url(\add_query_arg($args, $base));
    }

    private function licPageUrl(): string
    {
        return \get_admin_url(\get_current_blog_id(), 'admin.php?page=content-egg-lic');
    }

    private function fmt(int $ts): string
    {
        return $ts > 0 ? \date_i18n(\get_option('date_format'), $ts) : '';
    }

    private function enforcementSuffix(array $s): string
    {
        if (empty($s['locked']))
        {
            return '';
        }
        if (!empty($s['blocked']))
        {
            return ' ' . \esc_html__('Content Egg features are now disabled.', 'content-egg');
        }
        if (!empty($s['grace_ends_ts']))
        {
            return ' ' . sprintf(
                \esc_html__('Content Egg features will be disabled on %s unless resolved.', 'content-egg'),
                \esc_html($this->fmt((int) $s['grace_ends_ts']))
            );
        }
        return '';
    }

    private function isLicPage(): bool
    {
        return (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'admin.php'
            && isset($_GET['page']) && $_GET['page'] === 'content-egg-lic');
    }

    /**
     * Derive a render descriptor from cached status + lock state. Pure.
     *
     * @param array       $cachedData    LicenseGate::getCachedStatus() (or [])
     * @param string      $currentDomain LicenseUtils::domain()
     * @param bool        $locked        LicenseGate::isLocked()
     * @param string|null $lockReason    LicenseGate::getLockReason()
     * @param int         $now           current unix time
     */
    public static function computeState(array $cachedData, string $currentDomain, bool $locked, $lockReason, int $now, bool $blocked = false, int $graceEndsTs = 0): array
    {
        $lic = (isset($cachedData['license']) && is_array($cachedData['license'])) ? $cachedData['license'] : null;
        $activated = $lic !== null;
        $checked = !empty($cachedData);

        $status     = $activated ? strtolower((string) ($lic['status'] ?? '')) : '';
        $receiving  = $activated ? (bool) ($lic['can_update'] ?? false) : false;
        $isLifetime = $activated ? (bool) ($lic['is_lifetime'] ?? false) : false;

        $expiryTs = ($activated && !empty($lic['current_period_end'])) ? (int) strtotime((string) $lic['current_period_end']) : 0;
        $daysLeft = $expiryTs > 0 ? (int) floor(($expiryTs - $now) / 86400) : null;

        $supportActive = $activated ? (bool) ($lic['is_support_active'] ?? false) : false;
        $supportTs     = ($activated && !empty($lic['support_expires_at'])) ? (int) strtotime((string) $lic['support_expires_at']) : 0;

        $expiringSoon   = (!$isLifetime && $receiving && $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 21);
        $updatesExpired = ($activated && !$isLifetime && !$receiving && !$locked);

        $activatedOn = $activated ? (string) ($cachedData['activation']['domain'] ?? '') : '';
        $digest      = $activated ? (string) ($cachedData['activation']['domain_sha256'] ?? '') : '';
        $current     = self::normalizeDomain($currentDomain);

        // The cached domain STRING is not trustworthy. Hosts that serve a site under a
        // temporary hostname rewrite their own domain out of everything WordPress saves
        // — Hostinger's preview-domain mu-plugin filters `pre_update_option` and even
        // raw `query`, so it silently edits this cache after we store it. The server's
        // hex digest carries no hostname and survives that rewriting.
        //
        // The digest may only CLEAR a mismatch, never raise one: that keeps a
        // normalisation difference between client and server from manufacturing a
        // false accusation, and leaves installs talking to an older server (no digest)
        // on exactly the behaviour they have today.
        //
        // An empty $currentDomain means home_url() was unparseable. An unknown identity
        // is not evidence of a wrong one, so it never accuses.
        $stringMismatch = ($activatedOn !== '' && $current !== ''
            && self::normalizeDomain($activatedOn) !== $current);
        $digestClears   = ($digest !== '' && $current !== ''
            && hash_equals($digest, hash('sha256', $current)));

        $domainMismatch = ($activated && !$locked && $stringMismatch && !$digestClears);

        // A mismatch is a LOCAL heuristic and must never contradict the server. While
        // the server reports an active, updating licence, this site is licensed — the
        // most it can honestly say is "this looks like a copy".
        $mismatchAdvisory = ($domainMismatch && $status === 'active' && $receiving);

        return array(
            'activated'         => $activated,
            'checked'           => $checked,
            'status'            => $status,
            'receiving_updates' => $receiving,
            'is_lifetime'       => $isLifetime,
            'expiry_ts'         => $expiryTs,
            'days_left'         => $daysLeft,
            'expiring_soon'     => $expiringSoon,
            'updates_expired'   => $updatesExpired,
            'support_active'    => $supportActive,
            'support_ts'        => $supportTs,
            'locked'            => $locked,
            'lock_reason'       => $lockReason,
            'blocked'           => $blocked,
            'grace_ends_ts'     => $graceEndsTs,
            'domain_mismatch'   => $domainMismatch,
            'mismatch_advisory' => $mismatchAdvisory,
            'activated_on'      => $activatedOn,
        );
    }

    private static function normalizeDomain(string $d): string
    {
        return preg_replace('~^www\.~', '', strtolower(trim($d)));
    }

    /**
     * Which status card the License page should draw. Pure, so the ordering can
     * be tested without WordPress.
     *
     * The lock states come FIRST, ahead of the `checked` test. A lock is decided
     * by the server and stored on the install; it does not live in the status
     * cache, and cacheStatus() only ever runs after a SUCCESSFUL response. So an
     * install rejected on its very first contact — a key that is not ours, a
     * revoked seat, a deleted license — never caches anything, and testing
     * `checked` first left exactly those sites rendering no card at all: a bare
     * form, no reason given, on a plugin whose features had already been
     * disabled. Ordering the lock ahead of it costs nothing elsewhere, because a
     * lock is newer information than any payload the cache still holds.
     *
     * @param array $s      computeState() output
     * @param bool  $hasKey whether a license key is stored
     * @return string none|revoked|suspended|not_found|unverified|expired|mismatch|lifetime|dated
     */
    public static function cardVariant(array $s, bool $hasKey): string
    {
        if (!$hasKey)
        {
            return 'none'; // the activation form in the footer is the whole page
        }

        if (!empty($s['locked']))
        {
            if (($s['lock_reason'] ?? null) === 'TOKEN_REVOKED')
            {
                return 'revoked';
            }
            return ($s['lock_reason'] ?? null) === 'LICENSE_NOT_FOUND' ? 'not_found' : 'suspended';
        }

        if (empty($s['checked']))
        {
            // A key is stored but no verdict has ever come back, and nothing has
            // locked this install: a rejection the gate does not lock on, or
            // keywordrush.com unreachable from this host. Say so rather than
            // render an empty page.
            return 'unverified';
        }

        if (!empty($s['updates_expired']))
        {
            return 'expired';
        }
        if (!empty($s['domain_mismatch']) && empty($s['mismatch_advisory']))
        {
            return 'mismatch';
        }
        return !empty($s['is_lifetime']) ? 'lifetime' : 'dated';
    }

    public function handleActions()
    {
        // Check again — force one live refresh.
        if (isset($_POST['cegg_cmd']) && $_POST['cegg_cmd'] === 'refresh')
        {
            if (
                \current_user_can('manage_options')
                && isset($_POST['cegg_lic_nonce'])
                && \wp_verify_nonce($_POST['cegg_lic_nonce'], 'cegg_lic_refresh')
            )
            {
                $this->gate->refreshStatus();
                foreach (array_keys(self::HIDE_TTL) as $type)
                {
                    DurableTransient::delete('cegg_hide_notice_' . $type);
                }
            }
            \wp_safe_redirect($this->licPageUrl());
            exit;
        }

        // Dismiss a global nudge.
        if (isset($_GET['cegg_hide_notice']))
        {
            $type = (string) $_GET['cegg_hide_notice'];
            if (
                isset(self::HIDE_TTL[$type])
                && isset($_GET['_cegg_nonce'])
                && \wp_verify_nonce($_GET['_cegg_nonce'], 'cegg_hide_notice')
            )
            {
                DurableTransient::set('cegg_hide_notice_' . $type, 1, self::HIDE_TTL[$type]);
            }
            \wp_safe_redirect(\remove_query_arg(array('cegg_hide_notice', '_cegg_nonce')));
            exit;
        }
    }

    public function renderGlobalNotices()
    {
        if ($this->isLicPage())
        {
            return; // the License-page panel covers everything here
        }
        $s = $this->state();
        if (!$s['activated'] || !$s['checked'])
        {
            return; // never nag before the first successful check
        }

        // Precedence: locked > expired > expiring > mismatch.
        if ($s['locked'] && $s['lock_reason'] === 'TOKEN_REVOKED')
        {
            $this->nudge(
                'lic_revoked',
                'error',
                __('This site’s Content Egg activation was revoked.', 'content-egg') . $this->enforcementSuffix($s),
                __('Manage license', 'content-egg'),
                self::ctaUrl(Plugin::panelUri, 'license_revoked', 'notice')
            );
            return;
        }
        if ($s['locked'])
        {
            $msg = $s['lock_reason'] === 'LICENSE_NOT_FOUND'
                ? __('Your Content Egg license was not found.', 'content-egg')
                : __('Your Content Egg license has been suspended.', 'content-egg');
            $this->nudge(
                'lic_suspended',
                'error',
                $msg . $this->enforcementSuffix($s),
                __('Contact support', 'content-egg'),
                self::ctaUrl(Plugin::supportUri, 'license_suspended', 'notice')
            );
            return;
        }
        if ($s['updates_expired'])
        {
            $this->nudge(
                'lic_expired',
                'error',
                sprintf(__('Your Content Egg updates expired on %s. Renew to restore updates, security fixes, and support.', 'content-egg'), $this->fmt($s['expiry_ts'])),
                __('Renew now', 'content-egg'),
                self::ctaUrl(Plugin::panelUri, 'license_expired', 'notice')
            );
            return;
        }
        if ($s['expiring_soon'])
        {
            $this->nudge(
                'lic_expiring',
                'warning',
                sprintf(__('Your Content Egg license expires on %1$s (%2$d days left). You will stop receiving updates.', 'content-egg'), $this->fmt($s['expiry_ts']), (int) $s['days_left']),
                __('Renew now', 'content-egg'),
                self::ctaUrl(Plugin::panelUri, 'license_expiring', 'notice')
            );
            return;
        }
        if ($s['domain_mismatch'])
        {
            $advisory = !empty($s['mismatch_advisory']);
            $this->nudge(
                'lic_mismatch',
                $advisory ? 'warning' : 'error',
                $advisory
                    ? __('This site’s address does not match the domain your Content Egg license is registered to. Your license is active and updates are working — if you moved or copied this site, re-activate it here.', 'content-egg')
                    : __('This site is not licensed. This license belongs to another domain, so running the plugin here is outside your license terms. Activate a license valid for this site (an additional license may be required), or deactivate and remove the plugin from this domain.', 'content-egg'),
                __('Manage license', 'content-egg'),
                self::ctaUrl(Plugin::panelUri, 'license_mismatch', 'notice')
            );
            return;
        }
    }

    /**
     * Advisory line appended to an Active card when this site's address does not
     * match the registered one. Deliberately does NOT claim the site is unlicensed —
     * the server said active, and that outranks a local hostname comparison.
     */
    private function mismatchNote(array $s): string
    {
        if (empty($s['mismatch_advisory']))
        {
            return '';
        }
        return ' ' . \esc_html__('This site’s address does not match the one this license is registered to. If you moved or copied this site, re-activate it here.', 'content-egg');
    }

    private function nudge(string $type, string $level, string $message, string $ctaLabel, string $ctaUrl)
    {
        if (DurableTransient::get('cegg_hide_notice_' . $type))
        {
            return;
        }
        $this->css();
        $dismiss = \add_query_arg(array(
            'cegg_hide_notice' => $type,
            '_cegg_nonce'      => \wp_create_nonce('cegg_hide_notice'),
        ));
        echo '<div class="notice notice-' . \esc_attr($level) . ' cegg-notice"><p>';
        echo '<span class="cegg-notice-msg">' . \esc_html($message) . '</span>';
        echo '<a class="button button-primary cegg-notice-cta" target="_blank" rel="noopener" href="' . $ctaUrl . '">' . \esc_html($ctaLabel) . '</a>';
        echo '<a class="cegg-notice-close" href="' . \esc_url($dismiss) . '" aria-label="' . \esc_attr__('Dismiss', 'content-egg') . '"><span class="dashicons dashicons-no-alt"></span></a>';
        echo '</p></div>';
    }

    /**
     * Render the License-page status card.
     *
     * @param string $footer pre-built HTML rendered inside the card (change-key
     *                       form, deactivate form). ALWAYS emitted — when no card
     *                       is drawn it falls back to a bare wrapper, otherwise
     *                       the activation form would vanish on a fresh install.
     */
    public function renderPanel(string $footer = '')
    {
        $this->css();

        if (!$this->renderCard($footer) && $footer !== '')
        {
            echo '<div class="cegg-lic-foot cegg-lic-foot-bare">' . $footer . '</div>';
        }
    }

    /** @return bool true when a status card was rendered */
    private function renderCard(string $footer): bool
    {
        $hasKey = (string) LicConfig::getInstance()->option('license_key') !== '';
        if (!$hasKey)
        {
            return false; // not activated — the activation form in the footer handles this
        }
        $s = $this->state();

        $manageDated  = array(__('Manage license', 'content-egg'), self::ctaUrl(Plugin::panelUri, 'license_panel', 'dated'), 'primary');

        $variant = self::cardVariant($s, $hasKey);

        // Precedence mirrors the global notices; see cardVariant().
        if ($variant === 'revoked')
        {
            $this->card(
                'error',
                'inactive',
                __('Inactive', 'content-egg'),
                array(array(__('Status', 'content-egg'), \esc_html__('This site’s activation was revoked.', 'content-egg'))),
                array(array(__('Manage license', 'content-egg'), self::ctaUrl(Plugin::panelUri, 'license_revoked', 'panel'), 'primary')),
                true,
                $this->enforcementSuffix($s),
                $footer
            );
            return true;
        }
        if ($variant === 'not_found' || $variant === 'suspended')
        {
            $isNotFound = ($variant === 'not_found');
            $this->card(
                $isNotFound ? 'error' : 'suspended',
                $isNotFound ? 'inactive' : 'suspended',
                $isNotFound ? __('Inactive', 'content-egg') : __('Suspended', 'content-egg'),
                array(array(__('Status', 'content-egg'), $isNotFound
                    ? \esc_html__('License not found.', 'content-egg')
                    : \esc_html__('Your license has been suspended.', 'content-egg'))),
                array(array(__('Contact support', 'content-egg'), self::ctaUrl(Plugin::supportUri, 'license_suspended', 'panel'), 'primary')),
                false,
                $this->enforcementSuffix($s),
                $footer
            );
            return true;
        }
        if ($variant === 'unverified')
        {
            // No verdict has ever come back for this key and nothing locked the
            // install. Report that plainly — the previous behaviour was to render
            // no card at all, which read as "everything is fine".
            $this->card(
                'neutral',
                'neutral',
                __('Not verified', 'content-egg'),
                array(array(__('Status', 'content-egg'), \esc_html__('This site has not been activated yet.', 'content-egg'))),
                array(array(__('Manage license', 'content-egg'), self::ctaUrl(Plugin::panelUri, 'license_unverified', 'panel'), 'primary')),
                true,
                \esc_html__('Your license key is saved, but keywordrush.com has not confirmed it for this site. Use “Check again”, or enter a different key below. If it keeps failing, your server may be blocking connections to keywordrush.com.', 'content-egg'),
                $footer
            );
            return true;
        }
        if ($variant === 'expired')
        {
            $facts = array(array(
                __('Updates', 'content-egg'),
                '<span class="cegg-lic-bad">' . sprintf(\esc_html__('Expired on %s', 'content-egg'), \esc_html($this->fmt($s['expiry_ts']))) . '</span>'
            ));
            if ($s['activated_on'] !== '')
            {
                $facts[] = array(__('Site', 'content-egg'), \esc_html($s['activated_on']));
            }
            $this->card(
                'error',
                'inactive',
                __('Inactive', 'content-egg'),
                $facts,
                array(array(__('Renew now', 'content-egg'), self::ctaUrl(Plugin::panelUri, 'license_expired', 'panel'), 'primary')),
                true,
                \esc_html__('Renew to restore updates, security fixes, and support.', 'content-egg'),
                $footer
            );
            return true;
        }

        // License is registered to a different domain. Outranks the Active cards so the
        // License page never shows a misleading "Active / <other domain>" card — but
        // ONLY when the server has not just told us the licence is active and updating.
        // In that case the Active card renders instead and carries mismatchNote(), so a
        // local hostname comparison can never declare a paying customer unlicensed.
        if ($variant === 'mismatch')
        {
            $facts = array(array(
                __('Status', 'content-egg'),
                '<span class="cegg-lic-bad">' . \esc_html__('Not licensed for this domain', 'content-egg') . '</span>'
            ));
            if ($s['activated_on'] !== '')
            {
                $facts[] = array(__('Assigned to', 'content-egg'), \esc_html($s['activated_on']));
            }
            $this->card(
                'error',
                'inactive',
                __('Inactive', 'content-egg'),
                $facts,
                array(array(__('Manage license', 'content-egg'), self::ctaUrl(Plugin::panelUri, 'license_mismatch', 'panel'), 'primary')),
                true,
                \esc_html__('This license belongs to another domain, so this site is not covered — running the plugin here is outside your license terms. Activate a license valid for this site (an additional license may be required), or deactivate and remove the plugin from this domain.', 'content-egg'),
                $footer
            );
            return true;
        }

        // Active — lifetime or dated.
        $facts = array();
        if ($variant === 'lifetime')
        {
            $facts[] = array(
                __('Updates', 'content-egg'),
                '<span class="cegg-lic-badge">' . \esc_html__('Lifetime', 'content-egg') . '</span> ' . \esc_html__('included', 'content-egg')
            );
            if ($s['support_ts'] > 0)
            {
                $facts[] = array(__('Support', 'content-egg'), $s['support_active']
                    ? sprintf(\esc_html__('Active until %s', 'content-egg'), \esc_html($this->fmt($s['support_ts'])))
                    : '<span class="cegg-lic-bad">' . sprintf(\esc_html__('Expired on %s', 'content-egg'), \esc_html($this->fmt($s['support_ts']))) . '</span>');
            }
            if ($s['activated_on'] !== '')
            {
                $facts[] = array(__('Site', 'content-egg'), \esc_html($s['activated_on']));
            }
            $this->card(
                'active',
                'active',
                __('Active', 'content-egg'),
                $facts,
                array(array(__('Manage / Extend support', 'content-egg'), self::ctaUrl(Plugin::panelUri, 'license_panel', 'lifetime'), 'primary')),
                true,
                \esc_html__('You are receiving automatic updates.', 'content-egg') . $this->mismatchNote($s),
                $footer
            );
            return true;
        }

        // The day count only earns its place once renewal is imminent. Outside the
        // expiring_soon window (21 days) it is noise next to the date — "336 days
        // left" is precision nobody acts on.
        $soon = $s['expiring_soon'];
        $value = $soon
            ? sprintf(\esc_html__('Until %1$s (%2$d days left)', 'content-egg'), \esc_html($this->fmt($s['expiry_ts'])), (int) $s['days_left'])
            : sprintf(\esc_html__('Until %s', 'content-egg'), \esc_html($this->fmt($s['expiry_ts'])));
        $facts[] = array(__('Updates', 'content-egg'), $soon ? '<span class="cegg-lic-warnt">' . $value . '</span>' : $value);
        if ($s['activated_on'] !== '')
        {
            $facts[] = array(__('Site', 'content-egg'), \esc_html($s['activated_on']));
        }
        $cta = $soon
            ? array(__('Renew now', 'content-egg'), self::ctaUrl(Plugin::panelUri, 'license_expiring', 'panel'), 'primary')
            : $manageDated;
        $this->card(
            $soon ? 'warn' : 'active',
            'active',
            __('Active', 'content-egg'),
            $facts,
            array($cta),
            true,
            \esc_html__('You are receiving automatic updates.', 'content-egg') . $this->mismatchNote($s),
            $footer
        );
        return true;
    }

    /**
     * Render the status card.
     *
     * @param string $accent   one of: active|lifetime|warn|error|suspended|neutral
     * @param string $pillCls  one of: active|inactive|suspended|neutral
     * @param array  $facts    list of [labelText, valueHtml] (value pre-escaped)
     * @param array  $ctas     list of [labelText, escapedUrl, style(primary|ghost)]
     * @param string $note     pre-escaped note html (or '')
     * @param string $footer   pre-built footer html rendered inside the card (or '')
     */
    private function card(string $accent, string $pillCls, string $pillLabel, array $facts, array $ctas, bool $showCheck, string $note = '', string $footer = '')
    {
        // The masked key is appended here rather than in each branch so every card
        // variant shows it — it matters most when something is wrong and support
        // needs to know which key this site is using.
        $key = (string) LicConfig::getInstance()->option('license_key');
        if ($key !== '')
        {
            $facts[] = array(
                __('License key', 'content-egg'),
                '<span class="cegg-lic-key">' . \esc_html(LicenseUtils::maskKey($key)) . '</span>'
            );
        }

        echo '<div class="cegg-lic-card cegg-lic-' . \esc_attr($accent) . '">';
        echo '<div class="cegg-lic-head">';
        echo '<span class="cegg-lic-title">' . self::logoSvg() . \esc_html__('Content Egg License', 'content-egg') . '</span>';
        echo '<span class="cegg-lic-pill cegg-lic-pill-' . \esc_attr($pillCls) . '"><i></i>' . \esc_html(strtoupper($pillLabel)) . '</span>';
        echo '</div>';

        if ($facts)
        {
            echo '<div class="cegg-lic-facts">';
            foreach ($facts as $f)
            {
                echo '<div class="cegg-lic-fact"><span class="k">' . \esc_html($f[0]) . '</span><span class="v">' . $f[1] . '</span></div>';
            }
            echo '</div>';
        }
        if ($note !== '')
        {
            echo '<div class="cegg-lic-note">' . $note . '</div>';
        }

        echo '<div class="cegg-lic-actions">';
        if ($showCheck)
        {
            $this->checkAgainButton();
        }
        foreach ($ctas as $cta)
        {
            $style = isset($cta[2]) ? $cta[2] : 'primary';
            $cls = $style === 'ghost' ? 'button' : 'button button-primary';
            echo '<a class="' . $cls . '" target="_blank" rel="noopener" href="' . $cta[1] . '">' . \esc_html($cta[0]) . '</a>';
        }
        echo '</div>';

        if ($footer !== '')
        {
            echo '<div class="cegg-lic-foot">' . $footer . '</div>';
        }

        echo '</div>';
    }

    private static function logoSvg(): string
    {
        return '<svg class="cegg-lic-logo" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">'
            . '<path d="M12 2.2l7 3v6c0 4.4-3 8.2-7 8.9-4-0.7-7-4.5-7-8.9v-6l7-3z" fill="#2271b1"/>'
            . '<path d="M8.7 12.2l2.2 2.2 4.4-4.6" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
            . '</svg>';
    }

    private function checkAgainButton()
    {
        echo '<form class="cegg-lic-refresh" action="' . \esc_url($this->licPageUrl()) . '" method="post">';
        echo '<input type="hidden" name="cegg_cmd" value="refresh" />';
        echo '<input type="hidden" name="cegg_lic_nonce" value="' . \esc_attr(\wp_create_nonce('cegg_lic_refresh')) . '" />';
        echo '<button type="submit" class="button">&#8635; ' . \esc_html__('Check again', 'content-egg') . '</button>';
        echo '</form>';
    }

    private function css()
    {
        static $done = false;
        if ($done)
        {
            return;
        }
        $done = true;
        echo '<style>'
            // global nudges keep native .notice styling; tidy spacing + an icon-only dismiss
            . '.cegg-notice{position:relative;padding-right:42px;}'
            . '.cegg-notice .cegg-notice-cta{margin-left:14px;vertical-align:baseline;}'
            . '.cegg-notice .cegg-notice-close{position:absolute;top:50%;right:7px;transform:translateY(-50%);'
            . 'display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;'
            . 'color:#787c82;text-decoration:none;border-radius:3px;}'
            . '.cegg-notice .cegg-notice-close:hover{color:#d63638;background:#f0f0f1;}'
            . '.cegg-notice .cegg-notice-close .dashicons{width:18px;height:18px;font-size:18px;line-height:1;}'
            // --- License-page status card (flat) ---
            . '.cegg-lic-card{position:relative;max-width:660px;margin:18px 0;padding:20px 22px 20px 24px;background:#fff;border:1px solid #dcdfe4;}'
            . '.cegg-lic-card::before{content:"";position:absolute;inset:0 auto 0 0;width:4px;background:var(--ei-accent,#9aa3af);}'
            . '.cegg-lic-active{--ei-accent:#16a34a;}'
            . '.cegg-lic-warn{--ei-accent:#e08600;}.cegg-lic-error{--ei-accent:#dc2626;}'
            . '.cegg-lic-suspended{--ei-accent:#7f1d1d;}.cegg-lic-neutral{--ei-accent:#9aa3af;}'
            . '.cegg-lic-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;}'
            . '.cegg-lic-title{display:flex;align-items:center;gap:9px;font-size:15px;font-weight:600;color:#101828;}'
            . '.cegg-lic-logo{width:20px;height:20px;flex:none;}'
            . '.cegg-lic-pill{display:inline-flex;align-items:center;gap:7px;padding:4px 11px;border-radius:999px;'
            . 'font-size:11px;font-weight:700;letter-spacing:.07em;}'
            . '.cegg-lic-pill i{width:7px;height:7px;border-radius:50%;background:currentColor;}'
            . '.cegg-lic-pill-active{color:#15803d;background:#dcfce7;}'
            . '.cegg-lic-pill-inactive{color:#b42318;background:#fee4e2;}'
            . '.cegg-lic-pill-suspended{color:#fff;background:#7f1d1d;}'
            . '.cegg-lic-pill-neutral{color:#475467;background:#f2f4f7;}'
            . '.cegg-lic-facts{display:grid;gap:11px;margin-bottom:18px;}'
            . '.cegg-lic-fact{display:flex;gap:16px;font-size:13.5px;line-height:1.45;}'
            . '.cegg-lic-fact .k{flex:0 0 96px;color:#667085;}'
            . '.cegg-lic-fact .v{color:#101828;font-weight:500;}'
            . '.cegg-lic-badge{display:inline-block;padding:1px 9px;border-radius:3px;font-size:12px;font-weight:700;'
            . 'background:#dcfce7;color:#15803d;letter-spacing:.02em;}'
            . '.cegg-lic-bad{color:#b42318;font-weight:600;}.cegg-lic-warnt{color:#b54708;font-weight:600;}'
            . '.cegg-lic-note{margin:-6px 0 18px;font-size:13px;color:#667085;}'
            . '.cegg-lic-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}'
            . '.cegg-lic-refresh{margin:0;}'
            // --- masked key + card footer ---
            . '.cegg-lic-key{font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;'
            . 'font-size:13px;letter-spacing:.13em;background:#f2f4f7;padding:2px 9px;border-radius:4px;color:#101828;}'
            . '.cegg-lic-foot{display:flex;align-items:center;gap:16px;flex-wrap:wrap;'
            . 'margin-top:18px;padding-top:14px;border-top:1px solid #e6e8eb;font-size:13px;line-height:1.6;}'
            // Not activated yet: give the bare activation form the same white card
            // chrome and column width as the status card that replaces it, so the
            // page does not jump from a full-width form to a 660px card.
            . '.cegg-lic-foot-bare{display:block;max-width:660px;margin:18px 0 0;'
            . 'padding:20px 22px;border:1px solid #dcdfe4;background:#fff;border-top:1px solid #dcdfe4;}'
            . '.cegg-lic-foot-bare .form-table{margin-top:0;}'
            . '.cegg-lic-foot-bare .form-table th{width:1%;white-space:nowrap;padding:0 16px 14px 0;}'
            . '.cegg-lic-foot-bare .form-table td{padding:0 0 14px;}'
            . '.cegg-lic-foot-bare .form-table .description{margin:6px 0 0;}'
            . '.cegg-lic-foot-bare p.submit{margin:0;padding:0;}'
            . '.cegg-lic-change[open]{flex:1 1 100%;}'
            . '.cegg-lic-change>summary{display:inline-block;cursor:pointer;'
            . 'color:#2271b1;list-style:none;padding:0;font-size:13px;line-height:1.6;}'
            . '.cegg-lic-change>summary::-webkit-details-marker{display:none;}'
            . '.cegg-lic-change>summary:hover{color:#135e96;}'
            . '.cegg-lic-change[open]>summary{color:#667085;margin-bottom:4px;}'
            . '.cegg-lic-change .form-table{margin-top:0;}'
            . '.cegg-lic-change .form-table th{width:1%;white-space:nowrap;padding:12px 16px 12px 0;}'
            . '.cegg-lic-change .form-table td{padding:12px 0;}'
            . '.cegg-lic-change p.submit{margin:4px 0 0;padding:0;}'
            . '.cegg-lic-deact{margin:0;}'
            . '.cegg-lic-change + .cegg-lic-deact::before{content:"·";margin-right:16px;color:#c3c9d1;}'
            . '.cegg-lic-linkbtn{background:none;border:0;padding:0;margin:0;cursor:pointer;'
            . 'color:#b32d2e;font-family:inherit;font-size:13px;line-height:1.6;text-decoration:none;}'
            . '.cegg-lic-linkbtn:hover{color:#8a2424;text-decoration:underline;}'
            . '</style>';
    }
}
