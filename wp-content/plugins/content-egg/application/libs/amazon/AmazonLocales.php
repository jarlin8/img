<?php

namespace ContentEgg\application\libs\amazon;

defined('\ABSPATH') || exit;

/**
 * AmazonLocales class
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 *
 * @link: https://webservices.amazon.com/paapi5/documentation/locale-reference.html
 * @link: https://webservices.amazon.com/paapi5/documentation/common-request-parameters.html#host-and-region
 * @link: https://affiliate-program.amazon.com/creatorsapi/docs/en-us/get-started/using-curl
 *
 */
class AmazonLocales
{
    // Locale tuple indexes (kept for backwards compatibility)
    const IDX_NAME        = 0;
    const IDX_DOMAIN      = 1;
    const IDX_API_HOST    = 2;
    const IDX_REGION      = 3;
    const IDX_CURRENCY    = 4;
    const IDX_ADSYSTEM_NS = 5;

    // Creators API constants
    const CREATORS_API_BASE_URL     = 'https://creatorsapi.amazon';
    const CREATORS_API_CATALOG_PATH = '/catalog/v1';

    // Creators API "region groups"
    const CREATORS_GROUP_NA = 'NA';
    const CREATORS_GROUP_EU = 'EU';
    const CREATORS_GROUP_FE = 'FE';

    static public $locales = array(
        'au' => array('Australia', 'amazon.com.au', 'webservices.amazon.com.au', 'us-west-2', 'AUD', 'ws-fe'),
        'br' => array('Brazil', 'amazon.com.br', 'webservices.amazon.com.br', 'us-east-1', 'BRL', 'ws-na'),
        'ca' => array('Canada', 'amazon.ca', 'webservices.amazon.ca', 'us-east-1', 'CAD', 'ws-na'),
        'eg' => array('Egypt', 'amazon.eg', 'webservices.amazon.eg', 'eu-west-1', 'EGP', 'ws-eu'),
        'fr' => array('France', 'amazon.fr', 'webservices.amazon.fr', 'eu-west-1', 'EUR', 'ws-eu'),
        'de' => array('Germany', 'amazon.de', 'webservices.amazon.de', 'eu-west-1', 'EUR', 'ws-eu'),
        'in' => array('India', 'amazon.in', 'webservices.amazon.in', 'eu-west-1', 'INR', 'ws-eu'),
        'ie' => array('Ireland', 'amazon.ie', 'webservices.amazon.ie', 'eu-west-1', 'EUR', 'ws-eu'),
        'it' => array('Italy', 'amazon.it', 'webservices.amazon.it', 'eu-west-1', 'EUR', 'ws-eu'),
        'jp' => array('Japan', 'amazon.co.jp', 'webservices.amazon.co.jp', 'us-west-2', 'JPY', 'ws-fe'),
        'mx' => array('Mexico', 'amazon.com.mx', 'webservices.amazon.com.mx', 'us-east-1', 'MXN', 'ws-na'),
        'nl' => array('Netherlands', 'amazon.nl', 'webservices.amazon.nl', 'eu-west-1', 'EUR', 'ws-eu'),
        'pl' => array('Poland', 'amazon.pl', 'webservices.amazon.pl', 'eu-west-1', 'PLN', 'ws-eu'),
        'sg' => array('Singapore', 'amazon.sg', 'webservices.amazon.sg', 'us-west-2', 'SGD', 'ws-fe'),
        'es' => array('Spain', 'amazon.es', 'webservices.amazon.es', 'eu-west-1', 'EUR', 'ws-eu'),
        'se' => array('Sweden', 'amazon.se', 'webservices.amazon.se', 'eu-west-1', 'SEK', 'ws-eu'),
        'tr' => array('Turkey', 'amazon.com.tr', 'webservices.amazon.com.tr', 'eu-west-1', 'TRY', 'ws-eu'),
        'ae' => array('United Arab Emirates', 'amazon.ae', 'webservices.amazon.ae', 'eu-west-1', 'AED', 'ws-eu'),
        'uk' => array('United Kingdom', 'amazon.co.uk', 'webservices.amazon.co.uk', 'eu-west-1', 'GBP', 'ws-eu'),
        'us' => array('United States', 'amazon.com', 'webservices.amazon.com', 'us-east-1', 'USD', 'ws-na'),
        'sa' => array('Saudi Arabia', 'amazon.sa', 'webservices.amazon.sa', 'eu-west-1', 'SAR', 'ws-eu'),
        'be' => array('Belgium', 'amazon.com.be', 'webservices.amazon.com.be', 'eu-west-1', 'EUR', 'ws-eu'),
    );

    static public function locales()
    {
        return self::$locales;
    }

    static public function getLocale($locale)
    {
        $locales = self::$locales;
        if (isset($locales[$locale]))
        {
            return $locales[$locale];
        }

        throw new \Exception('Locale ' . esc_html($locale) . ' does not exist.');
    }

    static public function getApiHost($locale)
    {
        $data = self::getLocale($locale);
        return $data[self::IDX_API_HOST];
    }

    static public function getAdsystemHost($locale)
    {
        $data = self::getLocale($locale);
        return $data[self::IDX_ADSYSTEM_NS] . '.amazon-adsystem.com';
    }

    static public function getApiEndpoint($locale)
    {
        return 'https://' . self::getApiHost($locale);
    }

    static public function getRegion($locale)
    {
        $data = self::getLocale($locale);
        return $data[self::IDX_REGION];
    }

    static public function getDomain($locale)
    {
        $data = self::getLocale($locale);
        return $data[self::IDX_DOMAIN];
    }

    static public function getCurrencyCode($locale)
    {
        $data = self::getLocale($locale);
        return $data[self::IDX_CURRENCY];
    }

    static public function getAdsystemEndpoint($locale)
    {
        return 'https://' . self::getAdsystemHost($locale);
    }

    /**
     * -----------------------------
     * Creators API helpers
     * -----------------------------
     */

    /**
     * Creators API base URL (same for all locales).
     *
     * @return string
     */
    static public function getCreatorsApiBaseUrl()
    {
        return self::CREATORS_API_BASE_URL;
    }

    /**
     * Creators API catalog base URL (same for all locales).
     *
     * @return string
     */
    static public function getCreatorsCatalogBaseUrl()
    {
        return self::CREATORS_API_BASE_URL . self::CREATORS_API_CATALOG_PATH;
    }

    /**
     * Get the Creators API marketplace domain for a locale.
     * Creators API expects a "www." marketplace value (used in x-marketplace header and marketplace param).
     *
     * @param string $locale
     * @return string
     */
    static public function getCreatorsMarketplace($locale)
    {
        $domain = self::getDomain($locale);
        return self::ensureWwwPrefix($domain);
    }

    /**
     * Get the Creators API region group for a locale: NA / EU / FE.
     * We derive this from the existing PA-API adsystem namespace in the locale tuple (ws-na/ws-eu/ws-fe).
     *
     * @param string $locale
     * @return string
     */
    static public function getCreatorsRegionGroup($locale)
    {
        $data = self::getLocale($locale);
        $ns = isset($data[self::IDX_ADSYSTEM_NS]) ? (string) $data[self::IDX_ADSYSTEM_NS] : '';

        switch ($ns)
        {
            case 'ws-na':
                return self::CREATORS_GROUP_NA;
            case 'ws-eu':
                return self::CREATORS_GROUP_EU;
            case 'ws-fe':
                return self::CREATORS_GROUP_FE;
        }

        // Fallback: best-effort based on locale code (should rarely be needed)
        $locale = strtolower((string) $locale);
        if (in_array($locale, array('us', 'ca', 'mx', 'br'), true))
        {
            return self::CREATORS_GROUP_NA;
        }
        if (in_array($locale, array('jp', 'sg', 'au'), true))
        {
            return self::CREATORS_GROUP_FE;
        }

        return self::CREATORS_GROUP_EU;
    }

    /**
     * Get Creators API credential version for a locale.
     *
     * @param string $locale
     * @param string $major_version 'v2' or 'v3'
     * @return string
     */
    static public function getCreatorsCredentialVersion($locale, $major_version = 'v2')
    {
        $group = self::getCreatorsRegionGroup($locale);

        if ($major_version === 'v3')
        {
            switch ($group)
            {
                case self::CREATORS_GROUP_NA:
                    return '3.1';
                case self::CREATORS_GROUP_EU:
                    return '3.2';
                case self::CREATORS_GROUP_FE:
                    return '3.3';
            }

            return '3.2';
        }

        switch ($group)
        {
            case self::CREATORS_GROUP_NA:
                return '2.1';
            case self::CREATORS_GROUP_EU:
                return '2.2';
            case self::CREATORS_GROUP_FE:
                return '2.3';
        }

        // Default to EU (most locales)
        return '2.2';
    }

    /**
     * Get Creators API OAuth2 token endpoint for a locale.
     *
     * @param string $locale
     * @param string $major_version 'v2' or 'v3'
     * @return string
     */
    static public function getCreatorsTokenEndpoint($locale, $major_version = 'v2')
    {
        $group = self::getCreatorsRegionGroup($locale);

        if ($major_version === 'v3')
        {
            // v3.x: Login with Amazon (LwA) token endpoints
            switch ($group)
            {
                case self::CREATORS_GROUP_NA:
                    return 'https://api.amazon.com/auth/o2/token';
                case self::CREATORS_GROUP_EU:
                    return 'https://api.amazon.co.uk/auth/o2/token';
                case self::CREATORS_GROUP_FE:
                    return 'https://api.amazon.co.jp/auth/o2/token';
            }

            return 'https://api.amazon.co.uk/auth/o2/token';
        }

        // v2.x: Cognito token endpoints
        switch ($group)
        {
            case self::CREATORS_GROUP_NA:
                return 'https://creatorsapi.auth.us-east-1.amazoncognito.com/oauth2/token';
            case self::CREATORS_GROUP_EU:
                return 'https://creatorsapi.auth.eu-south-2.amazoncognito.com/oauth2/token';
            case self::CREATORS_GROUP_FE:
                return 'https://creatorsapi.auth.us-west-2.amazoncognito.com/oauth2/token';
        }

        // Default to EU
        return 'https://creatorsapi.auth.eu-south-2.amazoncognito.com/oauth2/token';
    }

    /**
     * Build the full Creators API catalog operation endpoint for a locale, e.g.:
     *  https://creatorsapi.amazon/catalog/v1/getItems
     *
     * @param string $locale
     * @param string $operation LowerCamelCase operation name, e.g. "getItems", "searchItems"
     * @return string
     */
    static public function getCreatorsCatalogEndpoint($locale, $operation)
    {
        // $locale currently unused here, but kept in signature for symmetry and future per-locale routing.
        $operation = ltrim((string) $operation, '/');
        return self::getCreatorsCatalogBaseUrl() . '/' . $operation;
    }

    /**
     * Convenience method returning a single array with all Creators API routing values for a locale.
     *
     * @param string $locale
     * @param string $major_version 'v2' or 'v3'
     * @return array{
     *   marketplace:string,
     *   region_group:string,
     *   version:string,
     *   major_version:string,
     *   api_base:string,
     *   catalog_base:string,
     *   token_endpoint:string
     * }
     */
    static public function getCreatorsApiConfig($locale, $major_version = 'v2')
    {
        return array(
            'marketplace'    => self::getCreatorsMarketplace($locale),
            'region_group'   => self::getCreatorsRegionGroup($locale),
            'version'        => self::getCreatorsCredentialVersion($locale, $major_version),
            'major_version'  => $major_version,
            'api_base'       => self::getCreatorsApiBaseUrl(),
            'catalog_base'   => self::getCreatorsCatalogBaseUrl(),
            'token_endpoint' => self::getCreatorsTokenEndpoint($locale, $major_version),
        );
    }

    /**
     * Resolve the Creators API credential to use for a given locale.
     *
     * Creators credentials are scoped to a region group (NA/EU/FE), so a catalog
     * spanning multiple regions needs one credential per region. We look up the
     * region-specific pair (creators_credential_id_{na|eu|fe} / _secret_{…}) and,
     * when it is absent or only half-filled, fall back to the "Primary" pair
     * (creators_credential_id / creators_credential_secret). The Primary pair alone
     * is enough for a single-region setup and keeps existing installs working.
     *
     * @param string $locale e.g. 'de', 'us', 'jp'
     * @param array  $config assoc array of the module's creators_credential_* options
     * @return array{id:string, secret:string, group:string}
     */
    static public function getCreatorsCredentialForLocale($locale, array $config)
    {
        try
        {
            $group = self::getCreatorsRegionGroup($locale);
        }
        catch (\Exception $e)
        {
            // Unknown locale: no region-specific pair applies, use the Primary pair.
            $group = '';
        }

        $id = $secret = '';

        if ($group !== '')
        {
            $suffix = strtolower($group);
            $id     = isset($config['creators_credential_id_' . $suffix]) ? (string) $config['creators_credential_id_' . $suffix] : '';
            $secret = isset($config['creators_credential_secret_' . $suffix]) ? (string) $config['creators_credential_secret_' . $suffix] : '';
        }

        // Fall back to the Primary credential unless the region pair is fully set.
        if ($id === '' || $secret === '')
        {
            $id     = isset($config['creators_credential_id']) ? (string) $config['creators_credential_id'] : '';
            $secret = isset($config['creators_credential_secret']) ? (string) $config['creators_credential_secret'] : '';
        }

        return array('id' => $id, 'secret' => $secret, 'group' => $group);
    }

    /**
     * Detect the major credential version from the Credential ID format.
     *
     * v3.x IDs start with "amzn1." (e.g. amzn1.application-oa2-client.0eaf477fc06143e38eb35b8c7eb00641)
     * v2.x IDs are short alphanumeric strings (e.g. 2bddavlv1lcr9qro1rbo4g36jv)
     *
     * @param string $credential_id
     * @return string 'v2' or 'v3'
     */
    static public function detectCredentialMajorVersion($credential_id)
    {
        $credential_id = trim((string) $credential_id);

        if (stripos($credential_id, 'amzn1.') === 0)
        {
            return 'v3';
        }

        return 'v2';
    }

    /**
     * Ensure the marketplace domain has a "www." prefix (Creators API expects e.g. www.amazon.com).
     *
     * @param string $domain
     * @return string
     */
    static protected function ensureWwwPrefix($domain)
    {
        $domain = (string) $domain;
        if ($domain === '')
        {
            return $domain;
        }

        if (stripos($domain, 'www.') === 0)
        {
            return $domain;
        }

        return 'www.' . $domain;
    }
}
