<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * ScrapingdogScrap class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ScrapingdogScrap extends Scrap
{
    const SLUG = 'scrapingdog';

    public function applyTo(ScrapRequest $request, array $context)
    {
        $request->url = 'https://api.scrapingdog.com/scrape?api_key=' . urlencode($this->getToken())
            . '&url=' . urlencode($request->url) . '&dynamic=false';
    }
}
