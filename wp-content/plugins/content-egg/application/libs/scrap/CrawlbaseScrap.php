<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * CrawlbaseScrap class file
 *
 * Formerly ProxyCrawl. The saved option key stays proxycrawl_token so
 * existing credentials keep working.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class CrawlbaseScrap extends Scrap
{
    const SLUG = 'crawlbase';

    public function tokenOptionName()
    {
        return 'proxycrawl_token';
    }

    public function applyTo(ScrapRequest $request, array $context)
    {
        $query = http_build_query(array(
            'token' => $this->getToken(),
            'url'   => $request->url,
        ), '', '&');

        $request->url = 'https://api.crawlbase.com/' . '?' . $query;
    }
}
