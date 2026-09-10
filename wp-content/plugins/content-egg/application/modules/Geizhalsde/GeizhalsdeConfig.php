<?php

namespace ContentEgg\application\modules\Geizhalsde;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModuleConfig;

/**
 * GeizhalsdeConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class GeizhalsdeConfig extends AffiliateParserModuleConfig
{
    public function options()
    {
        $options = array(
            'user' => array(
                'title' => 'User <span class="cegg_required">*</span>',
                'description' => __('Geizhals API user or token ID used inside the signed JWT payload.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'required'),
                        'when' => 'is_active',
                        'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), 'User'),
                    ),
                ),
            ),
            'secret_key' => array(
                'title' => 'Secret Key <span class="cegg_required">*</span>',
                'description' => __('Geizhals API shared secret used to sign the Bearer JWT token for each request.', 'content-egg'),
                'callback' => array($this, 'render_password'),
                'default' => '',
                'validator' => array(
                    'trim',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'required'),
                        'when' => 'is_active',
                        'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), 'Secret Key'),
                    ),
                ),
            ),
            'entries_per_page' => array(
                'title' => __('Results', 'content-egg'),
                'description' => __('Specify the number of results to display for a single search query.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 10,
                'validator' => array(
                    'trim',
                    'absint',
                ),
            ),
            'entries_per_page_update' => array(
                'title' => __('Results for updates', 'content-egg'),
                'description' => __('Specify the number of results for automatic updates and autoblogging.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 6,
                'validator' => array(
                    'trim',
                    'absint',
                ),
            ),
            'n_offers' => array(
                'title' => __('Offers per product', 'content-egg'),
                'description' => __('How many merchant offers should be returned for each matched product. Set to 1 to treat one product as one offer in search results.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 10,
                'validator' => array(
                    'trim',
                    'absint',
                ),
            ),
            'campaignID' => array(
                'title' => __('Campaign ID', 'content-egg'),
                'description' => __('Optional Geizhals campaign ID appended to returned merchant offer links.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'merchant_domains' => array(
                'title' => __('Merchant domains', 'content-egg'),
                'description' => __('Optional comma-separated list of merchant domains to keep after the Geizhals API response is received. This is a local post-processing filter in Content Egg, not a native API request parameter. Example: proshop.de, galaxus.de', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'exclude_geizhals_best_price_link' => array(
                'title' => __('Exclude Geizhals best price link', 'content-egg'),
                'description' => __('Exclude offers whose effective domain resolves to geizhals.de. This is useful for skipping the Geizhals Best Price Link result, which is local post-processing after the API response, not a native API filter.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'lang' => array(
                'title' => __('Language', 'content-egg'),
                'description' => __('Language content should be returned in.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    'de' => 'de',
                    'en' => 'en',
                    'pl' => 'pl',
                ),
                'default' => 'de',
            ),
            'loc' => array(
                'title' => __('Locale', 'content-egg'),
                'description' => __('Limits results to products available for the selected country or region.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 'de',
                'validator' => array(
                    'trim',
                ),
            ),
            'image_size' => array(
                'title' => __('Image size', 'content-egg'),
                'description' => __('Image size returned by the API.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    'n' => __('Normal', 'content-egg') . ' [n]',
                    's' => __('Small', 'content-egg') . ' [s]',
                    't' => __('Tiny', 'content-egg') . ' [t]',
                ),
                'default' => 'n',
            ),
            'omit_description' => array(
                'title' => __('Omit descriptions', 'content-egg'),
                'description' => __('Do not request product descriptions from the API.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'omit_images' => array(
                'title' => __('Omit images', 'content-egg'),
                'description' => __('Do not request product images from the API.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'search_ngrams_only' => array(
                'title' => __('N-gram search only', 'content-egg'),
                'description' => __('Use only n-gram searching for broad or long-tail queries.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'filter_accessories' => array(
                'title' => __('Filter accessories', 'content-egg'),
                'description' => __('Skip accessories in free-text search results.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'pickup_only' => array(
                'title' => __('Pickup only', 'content-egg'),
                'description' => __('Return only offers that support pickup.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'save_img' => array(
                'title' => __('Save images', 'content-egg'),
                'description' => __('Save images to local server', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
        );

        $parent = parent::options();
        $options = array_merge($parent, $options);

        return self::moveRequiredUp($options);
    }
}
