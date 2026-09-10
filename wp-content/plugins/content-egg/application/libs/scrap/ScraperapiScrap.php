<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * ScraperapiScrap class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ScraperapiScrap extends Scrap
{
    const SLUG = 'scraperapi';

    public function applyTo(ScrapRequest $request, array $context)
    {
        $request->url = 'http://api.scraperapi.com?api_key=' . urlencode($this->getToken())
            . '&url=' . urlencode($request->url);
    }
}
