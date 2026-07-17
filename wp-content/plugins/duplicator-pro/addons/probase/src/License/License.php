<?php

/**
 * @package Duplicator
 */

namespace Duplicator\Addons\ProBase\License;

use DateTime;
use Duplicator\Libs\Snap\SnapUtil;
use Duplicator\Utils\Crypt\CryptCustom;
use Duplicator\Utils\ExpireOptions;
use Exception;

final class License
{
    /**
     * GENERAL SETTINGS
     */
    const EDD_DUPPRO_STORE_URL               = 'https://snapcreek.com';
    const EDD_DUPPRO_ITEM_NAME               = 'Duplicator Pro';
    const LICENSE_KEY_OPTION_NAME            = 'duplicator_pro_license_key';
    const LICENSE_CACHE_TIME                 = 1209600; // 14 DAYS IN SECONDS
    const LICENSE_CACHE_CLEAR_KEY            = 'dup_pro_clear_updater_cache';
    const EDD_API_CACHE_TIME                 = 172800; // 48 hours
    const UNLICENSED_SUPER_NAG_DELAY_IN_DAYS = 30;
    const FRONTEND_CHECK_DELAY               = 61; // Seconds, different fromgeneral frontend check to unsync
    const FRONTEND_CHECK_DELAY_OPTION_KEY    = 'license_check';

    /**
     * LICENSE STATUS
     */
    const STATUS_OUT_OF_LICENSES = -3;
    const STATUS_UNCACHED        = -2;
    const STATUS_UNKNOWN         = -1;
    const STATUS_VALID           = 0;
    const STATUS_INVALID         = 1;
    const STATUS_INACTIVE        = 2;
    const STATUS_DISABLED        = 3;
    const STATUS_SITE_INACTIVE   = 4;
    const STATUS_EXPIRED         = 5;

    /**
     * LICENSE TYPES
     */
    const TYPE_UNLICENSED    = 0;
    const TYPE_PERSONAL      = 1;
    const TYPE_FREELANCER    = 2;
    const TYPE_BUSINESS_GOLD = 3;

    /**
     * ACTIVATION REPONSE
     */
    const ACTIVATION_RESPONSE_OK         = 0;
    const ACTIVATION_RESPONSE_POST_ERROR = -1;
    const ACTIVATION_RESPONSE_INVALID    = -2;

    private static $edd_updater = null;

    /**
     * License check
     *
     * @return void
     */
    public static function check()
    {
        if (
            !is_admin() &&
            ExpireOptions::getUpdate(
                self::FRONTEND_CHECK_DELAY_OPTION_KEY,
                true,
                self::FRONTEND_CHECK_DELAY
            ) !== false
        ) {
            return;
        }

        $dpro_license_key = get_option(self::LICENSE_KEY_OPTION_NAME, '');
        if (empty($dpro_license_key)) {
            return;
        }

        $global = \DUP_PRO_Global_Entity::get_instance();

        // Don't bother checking updates if valid license key isn't filled in since that will just create unnecessary traffic
        if (
            ($global !== null) &&
            (!self::isValidOvrKey($dpro_license_key)) &&
            ($global->license_status !== self::STATUS_INVALID) &&
            ($global->license_status !== self::STATUS_UNKNOWN)
        ) {
            // Hook EDD updater added in object constructor, this object must be instantiated even if not used
            $edd_updater = self::getEddUpdater();

            // Clear cache
            if (SnapUtil::filterInputRequest(self::LICENSE_CACHE_CLEAR_KEY, FILTER_VALIDATE_BOOLEAN)) {
                $edd_updater->clear_version_cache();
            }
        }
    }

    /**
     * Return latest version of the plugin
     *
     * @return string | false
     */
    public static function getLatestVersion()
    {
        $version_info = null;
        $edd_updater  = self::getEddUpdater();

        $version_info = $edd_updater->get_cached_version_info();

        $latest_version = ($version_info === false) ? false : $version_info->new_version;

        return $latest_version;
    }

    /**
     * Clear version cache
     *
     * @return void
     */
    public static function clearVersionCache()
    {
        self::getEddUpdater()->clear_version_cache();
    }

    /**
     * Return license key
     *
     * @return string
     */
    public static function getLicenseKey()
    {
        return get_option(License::LICENSE_KEY_OPTION_NAME);
    }

    /**
     * Change license activation
     *
     * @param bool $activate if true activate license
     *
     * @return int license status
     */
    public static function changeLicenseActivation($activate)
    {
        $license = get_option(self::LICENSE_KEY_OPTION_NAME, '');
        if ($activate) {
            $api_params = array(
                'edd_action' => 'activate_license',
                'license'    => $license,
                'item_name'  => urlencode(self::EDD_DUPPRO_ITEM_NAME), // the name of our product in EDD,
                'url'        => home_url()
            );
        } else {
            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license'    => $license,
                'item_name'  => urlencode(self::EDD_DUPPRO_ITEM_NAME), // the name of our product in EDD,
                'url'        => home_url()
            );
        }

        // Call the custom API.
        global $wp_version;

        $agent_string = "WordPress/" . $wp_version;
        \DUP_PRO_Log::trace("Wordpress agent string $agent_string");

        $response = wp_remote_post(
            self::EDD_DUPPRO_STORE_URL,
            array(
                'timeout'    => 15, 'sslverify'  => false, 'user-agent' => $agent_string,
                'body'       => $api_params
            )
        );

        // make sure the response came back okay
        if (is_wp_error($response)) {
            if ($activate) {
                $action = 'activating';
            } else {
                $action = 'deactivating';
            }

            \DUP_PRO_Log::traceObject("Error $action $license", $response);

            return self::ACTIVATION_RESPONSE_POST_ERROR;
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));
        self::clearVersionCache();

        if ($activate) {
            // decode the license data
            if ($license_data->license == 'valid') {
                \DUP_PRO_Log::trace("Activated license $license");
                return self::ACTIVATION_RESPONSE_OK;
            } else {
                \DUP_PRO_Log::traceObject("Problem activating license $license", $license_data);
                return self::ACTIVATION_RESPONSE_INVALID;
            }
        } else {
            // check that license:deactivated and item:Duplicator Pro json
            if ($license_data->license == 'deactivated') {
                \DUP_PRO_Log::trace("Deactivated license $license");
                return self::ACTIVATION_RESPONSE_OK;
            } else {
                // problems activating
                //update_option('edd_sample_license_status', $license_data->license);
                \DUP_PRO_Log::traceObject("Problems deactivating license $license", $license_data);
                return self::ACTIVATION_RESPONSE_INVALID;
            }
        }
    }

    /**
     * Check if is valid key
     *
     * @param string $scrambledKey license key
     *
     * @return boolean
     */
    public static function isValidOvrKey($scrambledKey)
    {
        return true;
    }

    /**
     * Set license key
     *
     * @param string $scrambledKey license key
     *
     * @return void
     */
    public static function setOvrKey($scrambledKey)
    {
        if (self::isValidOvrKey($scrambledKey)) {
            $unscrambledKey = CryptCustom::unscramble($scrambledKey);

            $index = strpos($unscrambledKey, '_');

            if ($index !== false) {
                $index++;
                $count = substr($unscrambledKey, $index);

                /* @var $global \DUP_PRO_Global_Entity */
                $global = \DUP_PRO_Global_Entity::get_instance();

                $global->license_limit               = $count;
                $global->license_no_activations_left = false;
                $global->license_status              = self::STATUS_VALID;

                $global->save();

                \DUP_PRO_Log::trace("$unscrambledKey is an ovr key with license limit $count");

                update_option(self::LICENSE_KEY_OPTION_NAME, $scrambledKey);
            }
        } else {
            throw new Exception("Ovr key in wrong format: $scrambledKey");
        }
    }

    /**
     * Get standard key
     *
     * @param string $scrambledKey license key
     *
     * @return string
     */
    public static function getStandardKeyFromOvrKey($scrambledKey)
    {
        return 'duplicator';
    }

    /**
     * Read license data
     *
     * @param boolean $forceRefresh if true refresh license status
     *
     * @return object
     */
    public static function getLicenseData($forceRefresh = false)
    {
        static $license_data = null;

        if (is_null($license_data) || $forceRefresh) {
            \DUP_PRO_Log::trace("retrieving live license status");
            $license_key = get_option(self::LICENSE_KEY_OPTION_NAME, '');

            if ($license_key != '') {
                $api_params = array(
                    'edd_action' => 'check_license',
                    'license'    => $license_key,
                    'item_name'  => urlencode(self::EDD_DUPPRO_ITEM_NAME),
                    'url'        => home_url()
                );

                global $wp_version;
                $agent_string = "WordPress/" . $wp_version;

                $response = wp_remote_post(
                    self::EDD_DUPPRO_STORE_URL,
                    array(
                        'timeout'    => 15, 'sslverify'  => false, 'user-agent' => $agent_string,
                        'body'       => $api_params
                    )
                );

                if (is_wp_error($response)) {
                    \DUP_PRO_Log::trace("Error getting license check response for $license_key so leaving status alone");
                } else {
                    $license_data = json_decode(wp_remote_retrieve_body($response));
                    \DUP_PRO_Log::traceObject("license data in response returned", $response);
                    \DUP_PRO_Log::traceObject("license data returned", $license_data);
                }
            }
        }

        return $license_data;
    }

    /**
     * Get expiration date format
     *
     * @param string $format date format
     *
     * @return bool|string return expirtation date formatted or false on fail
     */
    public static function getExpirationDate($format = 'Y-m-d')
    {
        return 'Lifetime';
    }

    /**
     * return expiration license days
     *
     * @return int // PHP_INT_MAX is filetime
     */
    public static function getExpirationDays()
    {
        return PHP_INT_MAX;
    }

    /**
     * Get license status
     *
     * @param boolean $forceRefresh if true refresh license status
     *
     * @return int
     */
    public static function getLicenseStatus($forceRefresh = false)
    {
        return 'Valid';
    }

    /**
     * Return license statu string by status
     *
     * @param int $licenseStatus license status
     *
     * @return string
     */
    public static function getLicenseStatusString($licenseStatus)
    {
        return 'Valid';
    }

    /**
     * Get license type
     *
     * @return int
     */
    public static function getType()
    {
        /* @var $global \DUP_PRO_Global_Entity */
        $global = \DUP_PRO_Global_Entity::get_instance();
        $global->license_limit = PHP_INT_MAX;

        if ($global->license_limit < 0) {
            $license_type = self::TYPE_UNLICENSED;
        } elseif ($global->license_limit < 15) {
            $license_type = self::TYPE_PERSONAL;
        } elseif ($global->license_limit < 500) {
            $license_type = self::TYPE_FREELANCER;
        } elseif ($global->license_limit >= 500) {
            $license_type = self::TYPE_BUSINESS_GOLD;
        } else {
            $license_type = self::TYPE_PERSONAL;
        }

        return $license_type;
    }

    /**
     *
     * @return boolean
     */
    public static function isPersonal()
    {
        return self::getType() >= self::TYPE_PERSONAL;
    }

    /**
     *
     * @return boolean
     */
    public static function isFreelancer()
    {
        return self::getType() >= self::TYPE_FREELANCER;
    }

    /**
     *
     * @return boolean
     */
    public static function isBusiness()
    {
        return self::getType() >= self::TYPE_BUSINESS_GOLD;
    }

    /**
     *
     * @return boolean
     */
    public static function isGold()
    {
        return self::getType() >= self::TYPE_BUSINESS_GOLD;
    }

    /**
     * Get license status from status by string
     *
     * @param string $licenseStatusString license status string
     *
     * @return int
     */
    private static function getLicenseStatusFromString($licenseStatusString)
    {
        return 'Valid';
    }

    /**
     * Accessor that returns the EDD Updater singleton
     *
     * @return DuplicatorEddPluginUpdater
     */
    private static function getEddUpdater()
    {
        if (self::$edd_updater === null) {
            $dpro_license_key = get_option(self::LICENSE_KEY_OPTION_NAME, '');

            $dpro_edd_opts = array(
                'version'     => DUPLICATOR_PRO_VERSION,
                'license'     => $dpro_license_key,
                'item_name'   => self::EDD_DUPPRO_ITEM_NAME,
                'author'      => 'Snap Creek Software',
                'cache_time'  => self::EDD_API_CACHE_TIME,
                'wp_override' => true
            );

            self::$edd_updater = new DuplicatorEddPluginUpdater(
                self::EDD_DUPPRO_STORE_URL,
                DUPLICATOR____FILE,
                $dpro_edd_opts,
                \DUP_PRO_Constants::PLUGIN_SLUG
            );
        }

        return self::$edd_updater;
    }
}