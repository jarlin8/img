<?php

namespace Keywordrush\AffiliateEgg;

defined('\ABSPATH') || exit;

/**
 * ProxycrawlScrap class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2022 keywordrush.com
 */
class ProxycrawlScrap extends Scrap {

    const SLUG = 'proxycrawl';

    public function doAction($url)
    {
        if (!$this->needSendThrough($url))
            return $url;
        
        $url = 'https://api.proxycrawl.com/?token=' . urlencode($this->getToken()) . '&url=' . urlencode($url);
        $url = \apply_filters('affegg_parse_url_' . $this->getSlug(), $url);

        return $url;
    }

}
