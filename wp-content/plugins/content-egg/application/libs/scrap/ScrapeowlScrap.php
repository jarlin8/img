<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * ScrapeowlScrap class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ScrapeowlScrap extends Scrap
{
    const SLUG = 'scrapeowl';

    public function applyTo(ScrapRequest $request, array $context)
    {
        $request->url = 'https://api.scrapeowl.com/v1/scrape?api_key=' . urlencode($this->getToken())
            . '&json_response=false&url=' . urlencode($request->url);
    }
}
