<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * DecodoScrap class file
 *
 * Decodo (formerly Smartproxy) scraping API.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class DecodoScrap extends Scrap
{
    const SLUG     = 'decodo';
    const ENDPOINT = 'https://scraper-api.decodo.com/v2/scrape';

    /** Decodo's documented synchronous limit. */
    const TIMEOUT = 150;

    public function applyTo(ScrapRequest $request, array $context)
    {
        $body = array(
            'url'      => $request->url,
            'headless' => 'html',
        );

        $request->url     = self::ENDPOINT;
        $request->method  = 'POST';
        $request->body    = \wp_json_encode($body);
        $request->timeout = self::TIMEOUT;

        $request->headers['Authorization'] = 'Basic ' . $this->getToken();
        $request->headers['Content-Type']  = 'application/json';
        $request->headers['Accept']        = 'application/json';
    }

    /**
     * Decodo wraps the page in a JSON envelope: results[0].content.
     */
    public function filterResponseBody($body)
    {
        if (!is_string($body) || $body === '')
        {
            return $body;
        }

        $decoded = json_decode($body, true);

        if (!is_array($decoded) || empty($decoded['results']) || !is_array($decoded['results']))
        {
            return $body;
        }

        $first = reset($decoded['results']);

        if (!is_array($first) || !isset($first['content']))
        {
            return $body;
        }

        return (string) $first['content'];
    }
}
