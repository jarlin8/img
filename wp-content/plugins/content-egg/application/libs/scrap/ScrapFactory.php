<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * ScrapFactory class file
 *
 * Registry and selection for scraping service providers.
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ScrapFactory
{
    /** @var array slug => class */
    public static $services = array(
        ScraperapiScrap::SLUG  => ScraperapiScrap::class,
        ScrapingdogScrap::SLUG => ScrapingdogScrap::class,
        ScrapeowlScrap::SLUG   => ScrapeowlScrap::class,
        CrawlbaseScrap::SLUG   => CrawlbaseScrap::class,
        DecodoScrap::SLUG      => DecodoScrap::class,
        BrightdataScrap::SLUG  => BrightdataScrap::class,
    );

    /**
     * Human-readable provider names for settings dropdowns.
     * Not translated: these are brand names.
     */
    public static function labels()
    {
        return array(
            ScraperapiScrap::SLUG  => 'ScraperAPI',
            ScrapingdogScrap::SLUG => 'Scrapingdog',
            ScrapeowlScrap::SLUG   => 'Scrapeowl',
            CrawlbaseScrap::SLUG   => 'Crawlbase',
            DecodoScrap::SLUG      => 'Decodo',
            BrightdataScrap::SLUG  => 'Bright Data',
        );
    }

    /**
     * Dropdown entries for an "active scraping service" setting: Auto, plus
     * every provider that actually has credentials.
     *
     * Takes raw saved option values rather than a Config instance, because
     * ModuleConfig::options() runs inside the Config constructor before
     * option_values is populated.
     *
     * @param array|mixed $saved Raw option values; anything non-array is treated as empty.
     * @return array slug => label
     */
    public static function dropdownOptions($saved)
    {
        $dropdown = array(
            'auto' => __('Auto (any configured)', 'content-egg'),
        );

        $saved  = is_array($saved) ? $saved : array();
        $labels = self::labels();

        foreach (self::configured(new ScrapArrayConfig($saved)) as $slug)
        {
            $dropdown[$slug] = isset($labels[$slug]) ? $labels[$slug] : $slug;
        }

        return $dropdown;
    }

    /**
     * @param string $slug
     * @param object $config
     * @return Scrap|null
     */
    public static function create($slug, $config)
    {
        if (!isset(self::$services[$slug]))
        {
            return null;
        }

        $class = self::$services[$slug];

        if (!class_exists($class))
        {
            return null;
        }

        return new $class($config);
    }

    /**
     * Slugs of every provider whose credentials are present.
     *
     * @param object $config
     * @return array
     */
    public static function configured($config)
    {
        $configured = array();

        foreach (array_keys(self::$services) as $slug)
        {
            $provider = self::create($slug, $config);

            if ($provider && $provider->isConfigured())
            {
                $configured[] = $slug;
            }
        }

        return $configured;
    }

    /**
     * Pick the provider to use for this request.
     *
     * An explicit scraping_service choice wins when that provider is actually
     * configured. Otherwise fall back to the historical random pick, so
     * installs upgrading from before this setting existed are unaffected.
     *
     * @param object $config
     * @return Scrap|null
     */
    public static function resolve($config)
    {
        $configured = self::configured($config);

        if (!$configured)
        {
            return null;
        }

        $choice = (string) $config->option('scraping_service');

        if ($choice && $choice !== 'auto' && in_array($choice, $configured, true))
        {
            return self::create($choice, $config);
        }

        shuffle($configured);

        return self::create(reset($configured), $config);
    }
}
