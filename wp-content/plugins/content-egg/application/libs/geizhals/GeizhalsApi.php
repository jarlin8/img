<?php

namespace ContentEgg\application\libs\geizhals;

defined('\ABSPATH') || exit;

use ContentEgg\application\libs\RestClient;

use function ContentEgg\prnx;

/**
 * GeizhalsApi class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 *
 * @link: https://api.geizhals.net/gh/docs/?api_type=gh_api#/default/query_product
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'RestClient.php';

class GeizhalsApi extends RestClient
{
    const API_URI_BASE = 'https://api.geizhals.net/gh/v9';
    const DEFAULT_TOKEN_ID = 'geizhals';

    private $secret_key;
    private $token_id;

    protected $_responseTypes = array(
        'json',
    );

    public function __construct($secret_key, $token_id = self::DEFAULT_TOKEN_ID)
    {
        $this->secret_key = $secret_key;
        $this->token_id = $token_id ?: self::DEFAULT_TOKEN_ID;
        $this->setResponseType('json');
        $this->setUri(self::API_URI_BASE);
    }

    public function queryProduct(array $payload)
    {
        $response = $this->restPost('/query_product', wp_json_encode($payload), 'application/json');

        return $this->_decodeResponse($response);
    }

    public function bulkQueryProduct(array $payload)
    {
        $response = $this->restPost('/bulk_query/prod-data', wp_json_encode($payload), 'application/json');

        return $this->_decodeResponse($response);
    }

    public function searchProducts($query, $type = 'free', array $params = array())
    {
        $payload = array(
            'query' => (string) $query,
            'type' => (string) $type,
        );

        if ($params)
        {
            $payload['params'] = $params;
        }

        return $this->queryProduct($payload);
    }

    public function search($keyword, array $params = array())
    {
        return $this->searchProducts($keyword, 'free', $params);
    }

    public function bulkQueryProducts(array $ids, array $params = array(), $type = 'id')
    {
        $payload = array(
            'ids' => array_values(array_map('intval', $ids)),
            'type' => (string) $type,
        );

        if ($params)
        {
            $payload['params'] = $params;
        }

        return $this->bulkQueryProduct($payload);
    }

    public function restGet($path, ?array $query = null)
    {
        $queryString = $query ? http_build_query($query) : '';
        $this->setCustomHeaders($this->getDefaultHeaders(
            'GET',
            $queryString,
            ''
        ));

        return parent::restGet($path, $query);
    }

    public function restPost($path, $data = null, $enctype = null, $opts = array())
    {
        $requestBody = is_string($data) ? $data : wp_json_encode($data);

        $this->setCustomHeaders($this->getDefaultHeaders(
            'POST',
            '',
            (string) $requestBody
        ));

        return parent::restPost($path, $data, $enctype, $opts);
    }

    private function getDefaultHeaders(string $method, string $queryString, string $requestBody): array
    {
        return array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->createToken($method, $queryString, $requestBody),
        );
    }

    private function createToken(string $method, string $queryString, string $requestBody): string
    {
        $host = (string) parse_url(self::API_URI_BASE, PHP_URL_HOST);
        $fingerprint = implode('|', array(
            strtoupper($method),
            $host,
            $queryString,
            $requestBody,
        ));

        $payload = array(
            'token_id' => $this->token_id,
            'request_fingerprint' => hash('sha256', $fingerprint),
            'timestamp' => time(),
        );

        return $this->encodeJwt($payload, $this->secret_key);
    }

    private function encodeJwt(array $payload, string $secret): string
    {
        $header = array(
            'alg' => 'HS256',
            'typ' => 'JWT',
        );

        $segments = array(
            $this->base64UrlEncode(wp_json_encode($header)),
            $this->base64UrlEncode(wp_json_encode($payload)),
        );

        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, $secret, true);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
