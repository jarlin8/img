<?php

namespace ContentEgg\application\libs\allkeyshop;

defined('\ABSPATH') || exit;

use ContentEgg\application\libs\RestClient;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'RestClient.php';

/**
 * AllkeyshopApi class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class AllkeyshopApi extends RestClient
{
    const API_URI_BASE = 'https://api.allkeyshop.com/public/v1';

    // Requests left in the window before we pause.
    const RATE_LIMIT_THRESHOLD = 2;

    // Never sleep longer than one full window.
    const RATE_LIMIT_MAX_SLEEP = 60;

    // Deliberately short: over the rate limit the API stalls the connection
    // instead of returning 429, and a long timeout would consume the update
    // scheduler's budget one hung request at a time.
    const REQUEST_TIMEOUT = 10;

    private $rate_remaining = null;
    private $rate_reset = null;

    public function __construct($token)
    {
        $this->setUri(self::API_URI_BASE);
        $this->setCustomHeaders(array(
            'Authorization' => 'Bearer ' . trim((string) $token),
            'Accept' => 'application/json',
        ));
    }

    public function catalog(array $options = array())
    {
        $this->throttle();

        // The HTTP client is a shared static instance, so the timeout has to be
        // asserted per request rather than once in the constructor.
        self::getHttpClient()->setTimeout(self::REQUEST_TIMEOUT);

        $response = $this->restGet('/CatalogV2', $options);

        $this->readRateLimitHeaders();

        $data = json_decode($response, true);
        if (!is_array($data))
            throw new \Exception('AllKeyShop: malformed API response.');

        return $data;
    }

    private function readRateLimitHeaders()
    {
        $response = self::getHttpClient()->getLastResponse();
        if (!$response || \is_wp_error($response))
            return;

        $remaining = \wp_remote_retrieve_header($response, 'x-ratelimit-remaining');
        $reset = \wp_remote_retrieve_header($response, 'x-ratelimit-reset');

        $this->rate_remaining = ($remaining === '') ? null : (int) $remaining;
        $this->rate_reset = ($reset === '') ? null : (int) $reset;
    }

    /**
     * Pause when the token's request budget is nearly spent.
     *
     * Going over the limit does not produce a 429 - the connection hangs until
     * the client times out, which surfaces as a silently skipped post.
     */
    private function throttle()
    {
        if ($this->rate_remaining === null || $this->rate_reset === null)
            return;

        if ($this->rate_remaining > self::RATE_LIMIT_THRESHOLD)
            return;

        $wait = $this->rate_reset - time();
        if ($wait <= 0)
            return;

        sleep(min($wait + 1, self::RATE_LIMIT_MAX_SLEEP));

        $this->rate_remaining = null;
        $this->rate_reset = null;
    }
}
