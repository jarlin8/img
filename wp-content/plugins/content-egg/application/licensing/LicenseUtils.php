<?php

namespace ContentEgg\application\licensing;

defined('\ABSPATH') || defined('ContentEgg\application\licensing\TESTING') || exit;

/**
 * Pure licensing helpers — no WordPress option/HTTP state.
 */
class LicenseUtils
{
    public static function isDevelopment(): bool
    {
        return isset($_SERVER['KEYWORDRUSH_DEVELOPMENT'])
            && $_SERVER['KEYWORDRUSH_DEVELOPMENT'] === '16203273895503427';
    }

    public static function domain(): string
    {
        $home = \home_url('/');
        $parts = \wp_parse_url($home);
        $host = isset($parts['host']) ? strtolower((string) $parts['host']) : '';
        $host = preg_replace('~^www\.~', '', $host);
        return $host ?: '';
    }

    public static function uuid_v4(): string
    {
        if (function_exists('wp_generate_uuid4')) {
            return (string) \wp_generate_uuid4();
        }
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Accept BOTH legacy key shapes: 32-char (alnum + ~/_/-) and 36-char hyphenated.
     * Never reformat a stored key — only validate.
     */
    public static function isValidKeyFormat(string $key): bool
    {
        if ($key === '') {
            return false;
        }
        if (preg_match('/^[0-9a-zA-Z_~-]{32}$/', $key)) {
            return true;
        }
        if (preg_match('/^\w{8}-\w{4}-\w{4}-\w{4}-\w{12}$/', $key)) {
            return true;
        }
        return false;
    }

    /**
     * Partially mask a license key for display: keep the first and last 4
     * characters, replace the middle with bullets. Short/empty keys are fully
     * masked.
     *
     * Cosmetic only. This stops a key leaking into screenshots, screen shares
     * and support tickets — it is NOT an access control. An administrator can
     * always read the stored option directly.
     */
    public static function maskKey($key): string
    {
        $key = trim((string) $key);
        $len = strlen($key);
        if ($len === 0) {
            return '';
        }
        if ($len <= 8) {
            return str_repeat('•', max(4, $len));
        }

        $visible = 4;
        $dots = min(20, $len - ($visible * 2));
        return substr($key, 0, $visible) . str_repeat('•', $dots) . substr($key, -$visible);
    }
}
