<?php

namespace ContentEgg\application\components;

defined('\ABSPATH') || exit;

use ContentEgg\application\Plugin;
use ContentEgg\application\admin\LicConfig;
use ContentEgg\application\helpers\TemplateHelper;

/**
 * LManager class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2021 keywordrush.com
 */
class LManager {

    const CACHE_TTL = 86400;

    private $data = null;
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self;

        return self::$instance;
    }

    public function adminInit()
    {
        \add_action('admin_notices', array($this, 'displayNotice'));
        $this->hideNotice();
    }

    public function getData($force = false)
    {
        if (!LicConfig::getInstance()->option('license_key'))
            return array();

        if (!$force && $this->data !== null)
            return $this->data;

        $this->data = $this->getCache();
        if ($this->data === false || $force)
        {

            $data = $this->remoteRetrieve();
            if (!$data || !is_array($data))
                $data = array();

            $this->data = $data;
            $this->saveCache($this->data);
        }

        return $this->data;
    }

    public function remoteRetrieve()
    {
        if (!$response = Plugin::apiRequest(array('method' => 'POST', 'timeout' => 10, 'body' => $this->getRequestArray('license'))))
            return false;

        if (!$result = json_decode(\wp_remote_retrieve_body($response), true))
            return false;

        return $result;
    }

    public function saveCache($data)
    {
        \set_transient(Plugin::getShortSlug() . '_' . 'ldata', $data, self::CACHE_TTL);
    }

    public function getCache()
    {
        return \get_transient(Plugin::getShortSlug() . '_' . 'ldata');
    }

    public function deleteCache()
    {
        \delete_transient(Plugin::getShortSlug() . '_' . 'ldata');
    }

    private function getRequestArray($cmd)
    {
        return array('cmd' => $cmd, 'd' => parse_url(\site_url(), PHP_URL_HOST), 'p' => Plugin::product_id, 'v' => Plugin::version(), 'key' => LicConfig::getInstance()->option('license_key'));
    }

    public function isConfigPage()
    {
        if ($GLOBALS['pagenow'] == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'content-egg-lic')
            return true;
        else
            return false;
    }

    public function displayNotice()
    {
        if (LManager::isNulled() && time() > 1634198091)
        {
            $notice_date = \get_option(Plugin::slug . '_nulled_notice_date', 0);
            if ($notice_date && time() > $notice_date + 86400 * 3)
            {
                LManager::deactivateLic();
                return;
            }

            $this->displayNulledNotice();
            return;
        }

        if (!$data = LManager::getInstance()->getData())
            return;

        if ($data['activated_on'] && $data['activated_on'] != preg_replace('/^www\./', '', strtolower(parse_url(\site_url(), PHP_URL_HOST))))
        {
            $this->displayLicenseMismatchNotice();
            return;
        }

        if (time() >= $data['expiry_date'])
        {
            $this->displayExpiredNotice($data);
            return;
        }

        $days_left = floor(($data['expiry_date'] - time()) / 3600 / 24);
        if ($days_left >= 0 && $days_left <= 21)
        {
            $this->displayExpiresSoonNotice($data);
            return;
        }

        if ($this->isConfigPage())
        {
            $this->displayActiveNotice($data);
            return;
        }
    }

    public function displayActiveNotice(array $data)
    {
        $this->addInlineCss();
        $purchase_uri = '/product/purchase/1017';
        $days_left = floor(($data['expiry_date'] - time()) / 3600 / 24);

        echo '<div class="notice notice-success egg-notice"><p>';
        echo sprintf(__('License status: <span class="egg-label egg-label-%s">%s</span>.', 'content-egg'), strtolower($data['status']), strtoupper($data['status']));
        if ($data['status'] == 'active')
            echo ' ' . __('You are receiving automatic updates.', 'content-egg');
        echo '<br />' . sprintf(__('Expires at %s (%d days left).', 'content-egg'), gmdate('F d, Y H:i', $data['expiry_date']) . ' GMT', $days_left);
        echo '</p>';
        echo '<p>';
        $this->displayCheckAgainButton();

        echo ' ' . sprintf('<a class="button-primary" target="_blank" href="%s">%s</a>', Plugin::website . '/login?return=' . urlencode($purchase_uri), "&#10003; " . __('Extend now', 'content-egg'));
        if ((int) $data['extend_discount'])
            echo ' <small>' . sprintf(__('with a %d%% discount', 'content-egg'), $data['extend_discount']) . '</small>';

        echo '</p></div>';
    }

    public function displayExpiresSoonNotice(array $data)
    {
        if (\get_transient('cegg_hide_notice_lic_expires_soon') && !$this->isConfigPage())
            return;

        $this->addInlineCss();
        $purchase_uri = '/product/purchase/1017';
        $days_left = floor(($data['expiry_date'] - time()) / 3600 / 24);
        echo '<div class="notice notice-warning egg-notice">';
        echo '<p>';
        if (!$this->isConfigPage())
        {
            $hide_notice_uri = \add_query_arg(array('cegg_hide_notice' => 'lic_expires_soon', '_cegg_notice_nonce' => \wp_create_nonce('hide_notice')), $_SERVER['REQUEST_URI']);
            echo '<a href="' . $hide_notice_uri . '" class="egg-notice-close notice-dismiss">' . __('Dismiss', 'content-egg') . '</a>';
        }
        echo '<strong>' . __('License expires soon', 'content-egg') . '</strong><br />';
        echo sprintf(__('Your %s license expires at %s (%d days left).', 'content-egg'), Plugin::getName(), gmdate('F d, Y H:i', $data['expiry_date']) . ' GMT', $days_left);
        echo ' ' . __('You will not receive automatic updates, bug fixes, and technical support.', 'content-egg');
        echo '</p>';
        echo '<p>';
        $this->displayCheckAgainButton();
        echo ' ' . sprintf('<a class="button-primary" target="_blank" href="%s">%s</a>', Plugin::website . '/login?return=' . urlencode($purchase_uri), "&#10003; " . __('Extend now', 'content-egg'));
        if ((int) $data['extend_discount'])
            echo ' <span class="egg-label egg-label-success">' . sprintf(__('with a %d%% discount', 'content-egg'), $data['extend_discount']) . '</span>';
        echo '</p>';
        echo '</div>';
    }

    public function displayExpiredNotice(array $data)
    {
        if (\get_transient('cegg_hide_notice_lic_expired') && !$this->isConfigPage())
            return;

        $this->addInlineCss();
        $purchase_uri = '/product/purchase/1017';
        echo '<div class="notice notice-error egg-notice">';
        echo '<p>';

        if (!$this->isConfigPage())
        {
            $hide_notice_uri = \add_query_arg(array('cegg_hide_notice' => 'lic_expired', '_cegg_notice_nonce' => \wp_create_nonce('hide_notice')), $_SERVER['REQUEST_URI']);
            echo '<a href="' . $hide_notice_uri . '" class="egg-notice-close notice-dismiss">' . __('Dismiss', 'content-egg') . '</a>';
        }

        echo '<strong>' . __('License expired', 'content-egg') . '</strong><br />';
        echo sprintf(__('Your %s license expired on %s.', 'content-egg'), Plugin::getName(), gmdate('F d, Y H:i', $data['expiry_date']) . ' GMT');
        echo ' ' . __('You are not receiving automatic updates, bug fixes, and technical support.', 'content-egg');
        echo '</p>';
        echo '<p>';
        $this->displayCheckAgainButton();
        echo ' ' . sprintf('<a class="button-primary" target="_blank" href="%s">%s</a>', Plugin::website . '/login?return=' . urlencode($purchase_uri), "&#10003; " . __('Renew now', 'content-egg'));
        echo '</p></div>';
    }

    public function displayLicenseMismatchNotice()
    {
        $this->addInlineCss();
        echo '<div class="notice notice-error egg-notice"><p>';
        echo '<img src=" ' . \ContentEgg\PLUGIN_RES . '/img/logo.png' . '" width="40" />';
        echo '<strong>' . __('License mismatch', 'content-egg') . '</strong><br />';
        echo sprintf(__("Your %s license doesn't match your current domain.", 'content-egg'), Plugin::getName());
        echo ' ' . sprintf(__('If you wish to continue using the plugin then you must <a target="_blank" href="%s">revoke</a> the license and then <a href="%s">reactivate</a> it again or <a target="_blank" href="%s">buy a new license</a>.', 'content-egg'), Plugin::panelUri, \get_admin_url(\get_current_blog_id(), 'admin.php?page=content-egg-lic'), 'https://www.keywordrush.com/contentegg/pricing');
        echo '</p></div>';
    }

    public function displayCheckAgainButton()
    {
        echo '<form style="display: inline;" action=" ' . \get_admin_url(\get_current_blog_id(), 'admin.php?page=content-egg-lic') . '" method="POST">';
        echo '<input type="hidden" name="cegg_cmd" id="cegg_cmd" value="refresh" />';
        echo '<input type="hidden" name="nonce_refresh" value="' . \wp_create_nonce('license_refresh') . '"/>';
        echo '<input type="submit" name="submit3" id="submit3" class="button" value="&#8635; ' . __('Check again', 'content-egg') . '" />';
        echo '</form>';
    }

    public function hideNotice()
    {
        if (!isset($_GET['cegg_hide_notice']))
            return;

        if (!isset($_GET['_cegg_notice_nonce']) || !\wp_verify_nonce($_GET['_cegg_notice_nonce'], 'hide_notice'))
            return;

        $notice = $_GET['cegg_hide_notice'];

        if (!in_array($notice, array('lic_expires_soon', 'lic_expired')))
            return;

        if ($notice == 'lic_expires_soon')
            $expiration = 7 * 24 * 3600;
        elseif ($notice == 'lic_expired')
            $expiration = 90 * 24 * 3600;
        else
            $expiration = 0;

        \set_transient('cegg_hide_notice_' . $notice, true, $expiration);

        \wp_redirect(\remove_query_arg(array('cegg_hide_notice', '_cegg_notice_nonce'), \wp_unslash($_SERVER['REQUEST_URI'])));
        exit;
    }

    public function addInlineCss()
    {
        echo '<style>.egg-notice a.egg-notice-close {position:static;float:right;top:0;right0;padding:0;margin-top:-20px;line-height:1.23076923;text-decoration:none;}.egg-notice a.egg-notice-close::before{position: relative;top: 18px;left: -20px;}.egg-notice img {float:left;width:40px;padding-right:12px;}</style>';
    }

    public function displayNulledNotice()
    {
        $activation_date = \get_option(Plugin::slug . '_first_activation_date', false);
        if ($activation_date && $activation_date < time() + 86400 * 3)
            return;

        $notice_date = \get_option(Plugin::slug . '_nulled_notice_date');
        if (!$notice_date)
        {
            $notice_date = time();
            \update_option(Plugin::slug . '_nulled_notice_date', $notice_date);
        }
        $valid_date = $notice_date + 86400 * 2;

        $this->addInlineCss();
        echo '<div class="notice notice-error egg-notice" style="padding: 10px;">';
        echo '<img src=" ' . \ContentEgg\PLUGIN_RES . '/img/logo.png' . '" width="40" />';
        echo '<strong>Cracked WordPress Plugin Can Kill Your Site</strong><br />';
        echo sprintf('<p>You are using a cracked version of %s plugin. This is an illegal and dangerous copy of the plugin.', Plugin::getName());
        echo '<br/>Cracked plugins often have backdoors and other malware injected into code that is used to get full third-party access to your site, distribute SEO spam, viruses and redirect site visitors. Your site will be probably blacklisted by Google.</p>';
        echo '<p>Please note: You can purchase Content Egg only on our <a target="_blank" href="https://www.keywordrush.com/?utm_source=cegg&utm_medium=referral&utm_campaign=legal">official site</a>. If you purchased your pirated copy on any other site, we recommend requesting a refund and reinstalling the plugin (your existing settings and plugin data are safe!).</p>';
        echo '<p>The official version includes <u>direct support, automatic updates</u> and a guarantee of proper work.</p>';

        if ($valid_date > time())
        {
            echo sprintf('Use code <b>LEGAL25</b> for a 25%% discount (valid until %s).', TemplateHelper::dateFormatFromGmt($valid_date, false));
            echo '<br><br><a target="_blank" class="button button-primary button-large" href="https://www.keywordrush.com/contentegg/pricing?ref=LEGAL25&utm_source=cegg&utm_medium=referral&utm_campaign=legal">Apply Coupon</a>';
        } else
            echo '<a target="_blank" class="button button-primary button-large" href="https://www.keywordrush.com/contentegg/pricing?utm_source=cegg&utm_medium=referral&utm_campaign=legal">Buy Now</a>';

        echo '</div>';
    }

    public static function isNulled()
    {
        if (!Plugin::isPro())
            return false;

        $l = LicConfig::getInstance()->option('license_key', false);
        
        if (!$l && Plugin::isEnvato())
            return false;
        
        if (!LManager::isValidLicFormat($l))
            return true;
        
        if (in_array(md5($l), LManager::getNulledLics()))
            return true;
        
        return false;
    }

    public static function isValidLicFormat($value)
    {
        if (preg_match('/[^0-9a-zA-Z_~\-]/', $value))
            return false;
        if (strlen($value) != 32 && strlen($value) != 36)
            return false;
        return true;
    }

    public static function getNulledLics()
    {
        return array('782827cbd9dab148548f41184850a17d','bc57aa5923d803c465184623de14114e','1342e3cd2142a46010550cb8d1c07a4a','dd025e37d236de2346a09a51331c1232','fded028cb4a7f4e5300f4b909fb9ff22','f875624c61165879063a51e9239f112b','a5c2305222b9bea26a5611101c93db86','f6d39075bfceb776ebb6bf79c4b46114','357c950561096089e2275dab50483d61','c52fadb5a51c73aeb510932d75a6ebc4','d92d03899e674d265c54221de87f47ab','f3f8db4cb0837916c1c3ee961950bfdb','86d55dbe32b9bced65b25e6d3c3746e4','2fc7e4480a15ed988c18b6051a34c5b4','4a21fce420d531857b5c07368bf07072','e95cab97ed66287bffec97513943c453','60a9115ad107ba9778e761ecc57a2521','a282d805e6e7b5f8f96ddcec966809fc','50896af36231cad47384235bd074a24f','14b4065233f9bd21d4cf5d1daa6390ff','3050138fffd03b322fee0c6ceb9dc204','4530cd97c2f0e44a4fa57bee3f41b1db','9973938d5a865f7ddaf3f5fd13f61036','be201977c383f201b75ed5c2a5479b84','19b18b505d21b47da99f0a777f5d8f81','981540125d6dbc442da361de72eb70fc','477f79182b686d15531f9e5ff85d2ca8','63ee89bd518501b66097688a476f3e4b','732dd0b39b6483319f092301fc9a160b','62acfbe89a4435321b6b7782fc40a3dd','dc0bf117f2d2ca6e883b69bb7527964f','c027e96dc12536569be4f11aefb16bb2','cd9e459ea708a948d5c2f5a6ca8838cf','ca7c9cbad07b024f0433426f9a84f29f','b473c2a1fb60142527780b829ba0ba76','560e12b4143100d5324cf0ca1fd73c80','ea95322b3432c2d884cb787ee9257807','4d89b9ea00db777228ac587149b7b403','bf5a6da93604411b3fb95b5e5604c30c','aea42a0a8a870e7f1a035d67c62419c1','e9fa9b6f0766ecbe71acb1a672d58f18','db38f47ce3b82c04c0064328c0ef1d04','d382d0b8b1412281e94b94765ededde7','eb974e8d25c7d8e63d36d0610d759a29','ad8f6c5337c70afb96479682271452e8','23dd5e6dd6d6f05bf31fbad5f30e3f96','d40e669d1df6f64cb2f243cf17ed95f0','36a31db6bda7d2aeac517f2e943add8d','42d5f13e6f0817fbf0bd8a1b814b5da8','6fbdcfc6d3ab9bd5985274b3d386615e','2ccffae25b74950a13540ea699b3d576','b0dafccca4919b60f2aef18235b05f44','74f7ad3ab536baea28476b3aebbd2543','8c843a67c9795b6c7f64207fe669ff6c','d6b45003725cf6642414c437a012b292','94ca33c37f77c8dd37f07a4e29cef697','8fcef9fe9edf15355d2f596ba7379998','09182045db7edbe68635bc21377527c6','492474da5c68f1e50ea61921c97b12d3','78d167d9bbd33709ff0a6d908c50ca8a','a51cc9d0c45fe7f4a22ab2ce031e854d','bac6fa8846a73ba00dcd1b863dd0b90a','0ca193c5805110f2e9c3173040b015eb','3b5d75688342a5008893a27ed9278945','0af06f2257fad1d2543ff7e41fc4f0ea','ab7a7f8d13a683cfad86cbfe3d1bdc12','2b55c90553f3e35cea170c7d3449e4f9','2e78af248162a602dbcfabaa85affdd4','36dcee17c23dce6f6975f0710192acfe','7751be51d8932f4da27b5bd8cc620165','6d05d7139c2f3d3871c9b0cdf3982603','6ce822b6177738de4992989da6b8e4fa','70a2b469903dec06525f3d26d897fb40','427954306adbc7aa6fb1a4188d75679d','1e0a43725379325ce601a55ce82d103e','2166e4938975ba9b30ae0bd90a536fa2','d1d0d881ecd189422cecd45bc362ccc2','15f72ed03221006fda1fbf112981be03','33bbc11f6ac9a866f836c1c8f96d360d','e74bf2cde0104a108a4e41c9d518789f','fa549e08cc7a2637def7e4028f93ee31','463ce5c464c4b0ce5fee19ad2e82b89e','7adbfc9e5f9628ac732499c8dee43188','6ce23fc6794aa40d8f6e96b0d6d4b80b','18d18341724e616e6038b30cc1afb103','f1cab0a3fbe94fdce1cec5f5a48e2f68','15b1104e706129580e4bdbe796502b77','935c6b67a69c1b988bfb1e73f3ddc7bf','dd469b3e16bd8f151751d61445438ecb','abf338e526251db6f76392b44c48b86c','9ff9eba04febf154374798e67d086c14');
    }

    public static function deactivateLic()
    {
        \update_option(Plugin::slug . '_nulled_key', LicConfig::getInstance()->option('license_key'));
        \update_option(Plugin::slug . '_nulled_deactiv_date', time());
        \delete_option(LicConfig::getInstance()->option_name());
    }

}
