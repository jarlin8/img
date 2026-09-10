<?php

namespace ContentEgg\application\modules\Allkeyshop;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModuleConfig;

/**
 * AllkeyshopConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class AllkeyshopConfig extends AffiliateParserModuleConfig
{
    public static function getLocalesList()
    {
        return array(
            'de' => 'Deutsch (de)',
            'en' => 'English (en)',
            'es' => 'Espanol (es)',
            'fr' => 'Francais (fr)',
            'it' => 'Italiano (it)',
            'nl' => 'Nederlands (nl)',
            'pt' => 'Portugues (pt)',
        );
    }

    public static function getCurrencyList()
    {
        return array(
            'eur' => 'EUR',
            'gbp' => 'GBP',
            'usd' => 'USD',
        );
    }

    /**
     * "list_score" is deliberately absent: the API returns HTTP 404
     * ("list not found") for it unless a list_id is supplied, and this
     * module never sends one.
     */
    public static function getSortFieldList()
    {
        return array(
            'relevance' => __('Relevance', 'content-egg'),
            'popularity_score' => __('Popularity', 'content-egg'),
            'price' => __('Price', 'content-egg'),
            'rating' => __('Rating', 'content-egg'),
            'release_date' => __('Release date', 'content-egg'),
            'name' => __('Name', 'content-egg'),
            'random' => __('Random', 'content-egg'),
            'id' => __('Product ID', 'content-egg'),
        );
    }

    public static function getSortOrderList()
    {
        return array(
            'desc' => __('Descending', 'content-egg'),
            'asc' => __('Ascending', 'content-egg'),
        );
    }

    public static function getPriceModeList()
    {
        return array(
            '' => __('API default', 'content-egg'),
            'price' => __('Best price (coupons applied)', 'content-egg'),
            'price_card' => __('Card price', 'content-egg'),
            'price_paypal' => __('PayPal price', 'content-egg'),
            'original_price' => __('Original price', 'content-egg'),
        );
    }

    /*
     * getActiveLocalesList() is deliberately NOT implemented.
     *
     * AffiliateParserModule::getSearchFilters() adds a locale dropdown to the
     * product search modal for any config that defines it, but this module
     * reads the locale from its settings only - doRequest() never looks at
     * $query_params['locale'] - so that dropdown would change nothing when
     * used. Locale belongs in the module settings, where search and price
     * updates both read the same value and prices cannot drift between them.
     */
    public static function isValidCountryCode($value)
    {
        return (bool) preg_match('/^[A-Z]{2}$/', (string) $value);
    }

    public function options()
    {
        $options = array(
            'token' => array(
                'title' => __('API token', 'content-egg') . ' <span class="cegg_required">*</span>',
                'description' => __('Bearer token for the AllKeyShop Search API. Register for the affiliate program <a target="_blank" href="https://affiliate.allkeyshop.com/">here</a> and request an API token. Your affiliate ID is embedded in the token, so all offer links are attributed to you.', 'content-egg'),
                'callback' => array($this, 'render_password'),
                'default' => '',
                'validator' => array(
                    'trim',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'required'),
                        'when' => 'is_active',
                        'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), __('API token', 'content-egg')),
                    ),
                ),
            ),
            'locale' => array(
                'title' => __('Locale', 'content-egg'),
                'description' => __('Language used for product names and AllKeyShop links.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => self::getLocalesList(),
                'default' => 'en',
            ),
            'currency' => array(
                'title' => __('Currency', 'content-egg'),
                'description' => __('AllKeyShop supports EUR, GBP and USD only.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => self::getCurrencyList(),
                'default' => 'usd',
            ),
            'activation_country' => array(
                'title' => __('Activation country', 'content-egg'),
                'description' => __('Optional two-letter ISO 3166-1 country code, e.g. DE. Restricts results to offers that can be activated in that country. Leave empty for no restriction.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                    array('type' => 'filter', 'call' => 'strtoupper'),
                    'allow_empty',
                    array(
                        'call' => array(__CLASS__, 'isValidCountryCode'),
                        'message' => __('Activation country must be a two-letter ISO 3166-1 code, for example DE.', 'content-egg'),
                    ),
                ),
            ),
            'price_mode' => array(
                'title' => __('Price mode', 'content-egg'),
                'description' => __('Which price to store. The API default is the card price. "Best price" applies available coupon codes, so the stored price is lower than the merchant shows before the code is entered.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => self::getPriceModeList(),
                'default' => '',
            ),
            'sort_field' => array(
                'title' => __('Sort by', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => self::getSortFieldList(),
                'default' => 'relevance',
            ),
            'sort_order' => array(
                'title' => __('Sort order', 'content-egg'),
                'description' => __('Ascending is only meaningful for Price and Name. Relevance, Popularity and Rating must stay descending or the worst matches are returned first.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => self::getSortOrderList(),
                'default' => 'desc',
            ),
            'n_offers' => array(
                'title' => __('Offers per product', 'content-egg'),
                'description' => __('How many merchant offers should be returned for each matched product, 1 to 10. Set to 1 to treat one product as one offer. Higher values create one item per offer, which suits price comparison tables - note that 10 products at 10 offers each creates 100 items on a post.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 1,
                'validator' => array(
                    'trim',
                    'absint',
                ),
            ),
            'allow_accounts_on_games' => array(
                'title' => __('Allow game accounts', 'content-egg'),
                'description' => __('Include offers that sell a shared game account instead of a key.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'entries_per_page' => array(
                'title' => __('Results', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 10,
                'validator' => array(
                    'trim',
                    'absint',
                ),
            ),
            'entries_per_page_update' => array(
                'title' => __('Results for updates', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 6,
                'validator' => array(
                    'trim',
                    'absint',
                ),
            ),
            'save_img' => array(
                'title' => __('Save images', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
        );

        $options = array_merge(parent::options(), $options);

        return self::moveRequiredUp($options);
    }
}
