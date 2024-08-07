<?php

namespace ContentEgg\application\modules\AmazonNoApi;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModuleConfig;
use ContentEgg\application\libs\amazon\AmazonLocales;

/**
 * AmazonNoApiConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2023 keywordrush.com
 */
class AmazonNoApiConfig extends AffiliateParserModuleConfig
{
    public function options()
    {
        $parent = parent::options();
        $parent['ttl_items']['title'] .= '**';
        $parent['ttl_items']['default'] = 0;
        $parent['ttl_items']['description'] .= '<br><br><em>'  . sprintf(__('Please be aware that frequently updating prices for the NoAPI module can be challenging. For more detailed information on this matter, please refer to our <a target="_blanl" href="%s">guidelines</a>.', 'content-egg'), 'https://ce-docs.keywordrush.com/modules/affiliate/amazon-no-api-module') . '</em>';

        $options = array(
            'associate_tag' => array(
                'title' => __('Default Associate Tag', 'content-egg') . ' <span class="cegg_required">*</span>',
                'description' => __('An alphanumeric token that uniquely identifies you as an Associate. To obtain an Associate Tag, refer to <a target="_blank" href="https://webservices.amazon.com/paapi5/documentation/troubleshooting/sign-up-as-an-associate.html">Becoming an Associate</a>.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'required'),
                        'when' => 'is_active',
                        'message' => __('The "Tracking ID" can not be empty.', 'content-egg'),
                    ),
                ),
                'section' => 'default',
            ),
            'locale' => array(
                'title' => __('Default locale', 'content-egg') . '<span class="cegg_required">*</span>',
                'description' => __('Your Amazon Associates tag works only in the locale in which you register. If you want to be an Amazon Associate in more than one locale, you must register separately for each locale.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => self::getLocalesList(),
                'default' => self::getDefaultLocale(),
                'section' => 'default',
            ),
            'hide_prices' => array(
                'title' => __('Prices' . '**', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    'hide' => __('Hide prices', 'content-egg'),
                    'display' => __('Display prices', 'content-egg'),
                ),
                'default' => self::getHidePricesDefault(),
            ),
        );

        $options['ttl_items'] = $parent['ttl_items'];
        unset($parent['ttl_items']);

        $options = array_merge($options, array(

            'scraperapi_token' => array(
                'title' => __('Scraperapi API key' . '**', 'content-egg'),
                'description' => sprintf(__('Your <a target="_blanl" href="%s">scraperapi.com</a> token.', 'content-egg'), 'https://www.keywordrush.com/go/scraperapi')
                    . '<br><br><em>' . __('If Amazon has blocked your server IP, you can activate one of the scraping services to bypass this issue.', 'content-egg') . '</em>',
                'callback' => array($this, 'render_password'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'proxycrawl_token' => array(
                'title' => __('Crawlbase token' . '**', 'content-egg'),
                'description' => __('Your crawlbase.com token.', 'content-egg'),
                'callback' => array($this, 'render_password'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'scrapingdog_token' => array(
                'title' => __('Scrapingdog API key' . '**', 'content-egg'),
                'description' => __('Your scrapingdog.com token.', 'content-egg'),
                'callback' => array($this, 'render_password'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'entries_per_page' => array(
                'title' => __('Results', 'content-egg'),
                'description' => __('Specify the number of results to display for one search query.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 3,
                'validator' => array(
                    'trim',
                    'absint',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
                        'arg' => 10,
                        'message' => __('The field "Results" can not be more than 10.', 'content-egg'),
                    ),
                ),
                'section' => 'default',
            ),
            'entries_per_page_update' => array(
                'title' => __('Results for updates', 'content-egg'),
                'description' => __('Set the number of results for automatic updates and autoblogging.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 3,
                'validator' => array(
                    'trim',
                    'absint',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
                        'arg' => 10,
                        'message' => __('The field "Results" can not be more than 50.', 'content-egg'),
                    ),
                ),
                'section' => 'default',
            ),
            'link_type' => array(
                'title' => __('Link type', 'content-egg'),
                'description' => __('Type of partner links. Know more about amazon <a target="_blank" href="https://affiliate-program.amazon.com/gp/associates/help/t2/a11">90 day cookie</a>.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    'product' => 'Product page',
                    'add_to_cart' => 'Add to cart',
                ),
                'default' => 'product',
                'section' => 'default',
            ),
            'save_img' => array(
                'title' => __('Save images', 'content-egg'),
                'description' => __('Save images to local server', 'content-egg')
                    . ' <p class="description">' . __('Enabling this option may violate API rules.', 'content-egg') . '</p>',
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
                'section' => 'default',
            ),
            'show_small_logos' => array(
                'title' => __('Small logos', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'description' => __('Enabling this option may violate API rules.', 'content-egg') . ' '
                    . sprintf(__('Read more: <a target="_blank" href="%s">Amazon brand usage guidelines</a>.', 'content-egg'), 'https://advertising.amazon.com/ad-specs/en/policy/brand-usage'),
                'dropdown_options' => array(
                    'true' => __('Show small logos', 'content-egg'),
                    'false' => __('Hide small logos', 'content-egg'),
                ),
                'default' => 'false',
            ),
            'show_large_logos' => array(
                'title' => __('Large logos', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    'true' => __('Show large logos', 'content-egg'),
                    'false' => __('Hide large logos', 'content-egg'),
                ),
                'default' => 'true',
            ),
        ));

        foreach (self::getLocalesList() as $locale_id => $locale_name)
        {
            $options['associate_tag_' . $locale_id] = array(
                'title' => sprintf(__('Associate Tag for %s locale', 'content-egg'), $locale_name),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            );
        }

        $options = array_merge($parent, $options);

        return self::moveRequiredUp($options);
    }

    public static function getLocalesList()
    {
        $locales = array_keys(self::locales());
        sort($locales);

        return array_combine($locales, array_map('strtoupper', $locales));
    }

    public static function getDefaultLocale()
    {
        return 'us';
    }

    public static function getActiveLocalesList()
    {
        $locales = self::getLocalesList();
        $active = array();

        $default = self::getInstance()->option('locale');
        $active[$default] = $locales[$default];

        foreach ($locales as $locale => $name)
        {
            if ($locale == $default)
            {
                continue;
            }
            if (self::getInstance()->option('associate_tag_' . $locale))
            {
                $active[$locale] = $name;
            }
        }

        return $active;
    }

    public static function getDomainByLocale($locale)
    {
        return AmazonLocales::getDomain($locale);
    }

    public static function locales()
    {
        return AmazonLocales::locales();
    }

    public static function getHidePricesDefault()
    {
        return 'hide';
    }
}
