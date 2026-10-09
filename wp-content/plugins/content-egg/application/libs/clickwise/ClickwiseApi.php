<?php

namespace ContentEgg\application\libs\clickwise;

defined('\ABSPATH') || exit;

use ContentEgg\application\libs\RestClient;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'RestClient.php';

/**
 * ClickwiseApi class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 *
 * @link: https://partners.clickwise.net/developers/
 */
class ClickwiseApi extends RestClient
{
    const API_URI_BASE = 'https://partners.clickwise.net/api/v1/publisher';

    const REQUEST_TIMEOUT = 20;

    // Seconds to wait before the single retry of a 503.
    const RETRY_DELAY = 2;

    public function __construct($apiKey)
    {
        $this->setUri(self::API_URI_BASE);
        $this->setCustomHeaders(array(
            'X-API-Key' => trim((string) $apiKey),
            'Accept' => 'application/json',
        ));
    }

    /**
     * Keyword search. q is 3-200 characters, limit 1-100.
     */
    public function search(array $options)
    {
        return $this->request('/products/', $options);
    }

    /**
     * Current price and stock for up to 100 product ids and/or GTINs.
     */
    public function lookup(array $options)
    {
        return $this->request('/products/lookup/', $options);
    }

    private function request($path, array $options)
    {
        // The HTTP client is a shared static instance, so the timeout has to be
        // asserted per request rather than once in the constructor.
        self::getHttpClient()->setTimeout(self::REQUEST_TIMEOUT);

        try
        {
            $response = $this->restGet($path, $options);
        }
        catch (\Exception $e)
        {
            // A very broad search can return 503 on the first call while the
            // result is computed; Clickwise says one retry a moment later succeeds.
            if ($e->getCode() != 503)
                throw $e;

            sleep(self::RETRY_DELAY);
            $response = $this->restGet($path, $options);
        }

        $data = json_decode($response, true);
        if (!is_array($data))
            throw new \Exception('Clickwise: malformed API response.');

        return $data;
    }
}
