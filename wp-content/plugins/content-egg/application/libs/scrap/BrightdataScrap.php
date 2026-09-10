<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * BrightdataScrap class file
 *
 * Bright Data Web Unlocker, direct API access.
 *
 * @link https://docs.brightdata.com/scraping-automation/web-unlocker/send-your-first-request
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class BrightdataScrap extends Scrap
{
    const SLUG     = 'brightdata';
    const ENDPOINT = 'https://api.brightdata.com/request';

    /** Unlocking can involve a full browser render, so allow well over the 60s default. */
    const TIMEOUT = 120;

    /**
     * Amazon locale keys are already ISO 3166-1 alpha-2 apart from "uk".
     * Kept as an override map so new Amazon locales need no change here.
     */
    protected static $country_overrides = array(
        'uk' => 'gb',
    );

    public function isConfigured()
    {
        return (bool) $this->config->option('brightdata_token')
            && (bool) $this->config->option('brightdata_zone');
    }

    public function applyTo(ScrapRequest $request, array $context)
    {
        $body = array(
            'zone'   => (string) $this->config->option('brightdata_zone'),
            'url'    => $request->url,
            'format' => 'raw',
        );

        if ($country = $this->resolveCountry($context))
        {
            $body['country'] = $country;
        }

        $request->url     = self::ENDPOINT;
        $request->method  = 'POST';
        $request->body    = \wp_json_encode($body);
        $request->timeout = self::TIMEOUT;

        $request->headers['Authorization'] = 'Bearer ' . (string) $this->config->option('brightdata_token');
        $request->headers['Content-Type']  = 'application/json';
    }

    /**
     * @return string Country code, or '' to let Bright Data choose.
     */
    protected function resolveCountry(array $context)
    {
        if ($this->config->option('brightdata_country') === 'auto')
        {
            return '';
        }

        if (empty($context['locale']))
        {
            return '';
        }

        return self::localeToCountry($context['locale']);
    }

    /**
     * @param string $locale Amazon locale key, e.g. 'de', 'uk'
     * @return string ISO 3166-1 alpha-2 country code
     */
    public static function localeToCountry($locale)
    {
        $locale = strtolower((string) $locale);

        if (isset(self::$country_overrides[$locale]))
        {
            return self::$country_overrides[$locale];
        }

        return $locale;
    }
}
