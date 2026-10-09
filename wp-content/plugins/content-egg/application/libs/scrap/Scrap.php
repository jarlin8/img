<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * Scrap class file
 *
 * Base class for scraping service providers.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
abstract class Scrap
{
    const SLUG = '';

    /**
     * Any object exposing option($name, $default = null): a CE Config
     * instance or a ScrapArrayConfig. Deliberately untyped — CE's Config
     * predates this layer and implements no shared interface.
     *
     * @var object
     */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getSlug()
    {
        return static::SLUG;
    }

    /**
     * Option key holding the credential. Overridable so a provider can be
     * renamed without orphaning saved credentials.
     */
    public function tokenOptionName()
    {
        return static::SLUG . '_token';
    }

    public function getToken()
    {
        return (string) $this->config->option($this->tokenOptionName());
    }

    public function isConfigured()
    {
        return (bool) $this->getToken();
    }

    /**
     * Decorate the request so it routes through this provider.
     *
     * @param ScrapRequest $request
     * @param array        $context ['locale' => 'de']
     */
    abstract public function applyTo(ScrapRequest $request, array $context);

    /**
     * Inspect the raw response before its body is used, and throw if the
     * provider reports an error the HTTP status does not show. Default: no-op.
     *
     * @param array|\WP_Error $response wp_remote_request() result
     * @throws \Exception
     */
    public function checkResponse($response)
    {
    }

    /**
     * Unwrap a provider response envelope. Default: identity.
     *
     * @param string $body
     * @return string
     */
    public function filterResponseBody($body)
    {
        return $body;
    }
}
