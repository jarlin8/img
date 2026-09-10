<?php

namespace ContentEgg\application\components;

defined('\ABSPATH') || exit;

use ContentEgg\application\licensing\LicenseGate;
use ContentEgg\application\licensing\LicenseUtils;

/**
 * LManager — thin compatibility layer over LicenseGate.
 * Preserves the public surface other code may still reference.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class LManager
{
    private static $instance = null;
    private $gate;

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self;

        return self::$instance;
    }

    private function __construct()
    {
        $this->gate = new LicenseGate();
    }

    public function adminInit()
    {
    }

    public function getData($force = false)
    {
        if ($force)
            $this->gate->refreshStatus();

        return $this->gate->legacyStatusArray();
    }

    public function deleteCache()
    {
        \delete_option(LicenseGate::OPT_STATUS);
        \delete_option(LicenseGate::OPT_STATUS_AT);
    }

    public function isExpired(): bool
    {
        $data = $this->gate->legacyStatusArray();
        $expiry = $data['expiry_date'] ?? null;
        if (!is_numeric($expiry))
            return false;

        return time() >= (int) $expiry;
    }

    public static function isNulled()
    {
        return false;
    }

    public static function getNulledLics()
    {
        return array();
    }

    public static function isValidLicFormat($value)
    {
        return LicenseUtils::isValidKeyFormat((string) $value);
    }

    public static function deactivateLic()
    {
    }
}
