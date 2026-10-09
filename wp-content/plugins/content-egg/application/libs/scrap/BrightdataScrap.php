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
     * The Direct API answers 200 OK once a request reaches the unlocker; a
     * failure is reported in x-brd-error (+ x-brd-error-code), with the result
     * status in x-brd-status-code. Left alone, the error body parses as a page
     * with no products. Rate limits become a 429 so callers retry after a delay.
     *
     * A response without x-brd-error, such as the target site's own 404, is
     * the page's answer and passes through.
     *
     * @link https://docs.brightdata.com/products/web-unlocker/error-codes
     */
    public function checkResponse($response)
    {
        if (!is_array($response))
        {
            return;
        }

        $error = trim((string) \wp_remote_retrieve_header($response, 'x-brd-error'));

        if ($error === '')
        {
            return;
        }

        $error_code = trim((string) \wp_remote_retrieve_header($response, 'x-brd-error-code'));
        $status     = (int) \wp_remote_retrieve_header($response, 'x-brd-status-code');

        if (stripos($error_code, 'rate_limit') !== false)
        {
            $status = 429;
        }
        elseif ($status < 400)
        {
            $status = 502;
        }

        self::fail($status, $error_code !== '' ? $error . ' [' . $error_code . ']' : $error);
    }

    /**
     * Fallback for a rate-limit notice without x-brd-error headers: a short
     * plain-text body such as "A global adaptive rate limit has been applied.
     * Please retry in a few seconds." (global_rate_limit / bucket_rate_limit).
     *
     * @link https://docs.brightdata.com/products/scraper-studio/error-codes#rate-limit-variants
     */
    public function filterResponseBody($body)
    {
        if (is_string($body) && self::isRateLimitNotice($body))
        {
            self::fail(429, $body);
        }

        return $body;
    }

    /**
     * @param int    $status HTTP status to report
     * @param string $detail Provider message
     * @throws \Exception
     */
    protected static function fail($status, $detail)
    {
        $detail = \sanitize_text_field($detail);
        $detail = function_exists('mb_substr') ? mb_substr($detail, 0, 300) : substr($detail, 0, 300);

        throw new \Exception(
            \esc_html('HTTP request status fails: ' . $status . '. ' . strtoupper(self::SLUG) . ': ' . $detail),
            (int) $status
        );
    }

    /**
     * A real Amazon page is hundreds of KB of HTML; the notice is one line.
     */
    protected static function isRateLimitNotice($body)
    {
        if ($body === '' || strlen($body) > 1000 || stripos($body, '<html') !== false)
        {
            return false;
        }

        return stripos($body, 'rate limit') !== false || stripos($body, 'rate_limit') !== false;
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
