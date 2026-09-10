<?php

namespace ContentEgg\application\licensing;

defined('\ABSPATH') || defined('ContentEgg\application\licensing\TESTING') || exit;

/**
 * JSON transport for the keywordrush.com licensing API.
 * Always returns a normalized array envelope (never WP_Error):
 *   { ok, code, message, data, _http_status }.
 */
class LicenseClient
{
    const DEFAULT_BASE = 'https://www.keywordrush.com';

    public function getBaseUrl(): string
    {
        if (!LicenseUtils::isDevelopment()) {
            return self::DEFAULT_BASE;
        }
        $base = defined('CEGG_KEYWORDRUSH_BASE_URL') ? (string) \CEGG_KEYWORDRUSH_BASE_URL : self::DEFAULT_BASE;
        $base = (string) \apply_filters('cegg_keywordrush_base_url', $base);
        return rtrim($base, '/');
    }

    private function sslVerify(): bool
    {
        if (!LicenseUtils::isDevelopment()) {
            return true;
        }
        $v = defined('CEGG_KEYWORDRUSH_SSLVERIFY') ? (bool) \CEGG_KEYWORDRUSH_SSLVERIFY : true;
        return (bool) \apply_filters('cegg_keywordrush_sslverify', $v);
    }

    private function timeout(): int
    {
        $t = defined('CEGG_KEYWORDRUSH_TIMEOUT') ? (int) \CEGG_KEYWORDRUSH_TIMEOUT : 15;
        return (int) \apply_filters('cegg_keywordrush_timeout', $t);
    }

    public function get(string $path, array $headers = []): array
    {
        return $this->request('GET', $path, null, $headers);
    }

    public function post_json(string $path, array $body, array $headers = []): array
    {
        return $this->request('POST', $path, $body, $headers);
    }

    protected function request(string $method, string $path, ?array $jsonBody, array $headers): array
    {
        $url = $this->getBaseUrl() . $path;

        $args = [
            'method'    => $method,
            'timeout'   => $this->timeout(),
            'sslverify' => $this->sslVerify(),
            'headers'   => array_merge(
                ['Accept' => 'application/json'],
                $jsonBody !== null ? ['Content-Type' => 'application/json'] : [],
                $headers
            ),
        ];
        if ($jsonBody !== null) {
            $args['body'] = \wp_json_encode($jsonBody);
        }

        $resp = \wp_remote_request($url, $args);

        if (\is_wp_error($resp)) {
            return ['ok' => false, 'code' => 'NETWORK', 'message' => $resp->get_error_message(), 'data' => [], '_http_status' => 0];
        }

        $code = (int) \wp_remote_retrieve_response_code($resp);
        $body = (string) \wp_remote_retrieve_body($resp);
        $json = json_decode($body, true);

        if (!is_array($json)) {
            return ['ok' => false, 'code' => 'BAD_JSON', 'message' => 'Bad JSON from license server.', 'data' => [], '_http_status' => $code];
        }

        $json['_http_status'] = $code;
        return $json;
    }
}
