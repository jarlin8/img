<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * ScrapRequest class file
 *
 * A mutable description of one outbound scraping request. Providers decorate
 * it; AmazonClient replays it onto WpHttpClient.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ScrapRequest
{
    /** @var string Target URL. Providers may replace this with their endpoint. */
    public $url;

    /** @var string GET or POST. */
    public $method = 'GET';

    /** @var array Header name => value. */
    public $headers = array();

    /** @var string|null Raw request body. */
    public $body = null;

    /** @var int|null Timeout in seconds, or null for the client default. */
    public $timeout = null;

    public function __construct($url)
    {
        $this->url = $url;
    }
}
