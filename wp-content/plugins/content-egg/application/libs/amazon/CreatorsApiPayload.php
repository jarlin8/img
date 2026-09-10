<?php

namespace ContentEgg\application\libs\amazon;

defined('\ABSPATH') || exit;

/**
 * Helper for migrating PA-API payloads to Creators API payload format.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 *
 * - Converts PascalCase keys -> lowerCamelCase
 * - Ensures partnerTag, marketplace, partnerType defaults
 * - Converts Resources -> resources and normalizes resource strings:
 *     "ItemInfo.Title" -> "itemInfo.title"
 *     "Images.Primary.Small" -> "images.primary.small"
 */

class CreatorsApiPayload
{
    /**
     * Normalize payload.
     *
     * @param array $payload
     * @param array $context {
     *   @type string 'associate_tag'
     *   @type string 'marketplace'
     *   @type array  'defaults'
     * }
     * @return array
     */
    public static function normalize(array $payload, array $context)
    {
        $associate_tag = isset($context['associate_tag']) ? (string) $context['associate_tag'] : '';
        $marketplace   = isset($context['marketplace']) ? (string) $context['marketplace'] : '';
        $defaults      = isset($context['defaults']) && is_array($context['defaults']) ? $context['defaults'] : array();

        // 1) Convert keys recursively to lowerCamelCase
        $out = self::keysToLowerCamelRecursive($payload);

        // 2) Common parameter renames / compatibility:
        // PA-API uses "Resources" -> creators "resources"
        if (isset($out['resources']) && is_array($out['resources']))
        {
            $out['resources'] = self::normalizeResources($out['resources']);
        }

        // 3) Ensure required-ish fields
        if (!isset($out['partnerTag']) && $associate_tag !== '')
        {
            $out['partnerTag'] = $associate_tag;
        }
        if (!isset($out['partnerType']))
        {
            // PA-API requires PartnerType=Associates; keep default for compatibility
            $out['partnerType'] = 'Associates';
        }
        if (!isset($out['marketplace']) && $marketplace !== '')
        {
            $out['marketplace'] = $marketplace;
        }

        // 4) Apply per-operation defaults (e.g. itemIdType=ASIN)
        foreach ($defaults as $k => $v)
        {
            if (!isset($out[$k]))
            {
                $out[$k] = $v;
            }
        }

        return $out;
    }

    /**
     * Recursively convert associative array keys to lowerCamelCase.
     *
     * @param mixed $value
     * @return mixed
     */
    private static function keysToLowerCamelRecursive($value)
    {
        if (!is_array($value))
        {
            return $value;
        }

        $out = array();
        foreach ($value as $k => $v)
        {
            // Preserve numeric keys
            if (is_int($k))
            {
                $out[$k] = self::keysToLowerCamelRecursive($v);
                continue;
            }

            $newKey = self::toLowerCamel((string) $k);
            $out[$newKey] = self::keysToLowerCamelRecursive($v);
        }

        return $out;
    }

    /**
     * Convert a single key to lowerCamelCase.
     * - If it looks like UpperCamel/PascalCase, lower the first letter.
     * - Keeps existing camelCase as-is.
     *
     * @param string $key
     * @return string
     */
    private static function toLowerCamel($key)
    {
        $key = (string) $key;
        if ($key === '')
        {
            return $key;
        }

        // If key already contains underscores or hyphens, leave it
        if (strpos($key, '_') !== false || strpos($key, '-') !== false)
        {
            return $key;
        }

        // Lowercase the first character only: "ItemIds" -> "itemIds"
        return lcfirst($key);
    }

    /**
     * Normalize resource names from PA-API style to Creators style.
     * Example:
     *  "ItemInfo.Title" -> "itemInfo.title"
     *  "Images.Primary.Small" -> "images.primary.small"
     *
     * @param array $resources
     * @return array
     */
    private static function normalizeResources(array $resources)
    {
        $out = array();
        foreach ($resources as $r)
        {
            if (!is_string($r))
            {
                continue;
            }
            $out[] = self::normalizeResourceString($r);
        }
        return $out;
    }

    /**
     * Lower-case the first char of each dot-separated segment.
     *
     * @param string $resource
     * @return string
     */
    private static function normalizeResourceString($resource)
    {
        $resource = trim((string) $resource);
        if ($resource === '')
        {
            return $resource;
        }

        $parts = explode('.', $resource);
        foreach ($parts as $i => $p)
        {
            $p = trim($p);
            if ($p === '')
            {
                continue;
            }
            $parts[$i] = lcfirst($p);
        }
        return implode('.', $parts);
    }
}
