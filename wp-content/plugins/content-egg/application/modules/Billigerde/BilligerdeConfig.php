<?php

namespace ContentEgg\application\modules\Billigerde;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModuleConfig;

/**
 * BilligerdeConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class BilligerdeConfig extends AffiliateParserModuleConfig
{
    public function options()
    {
        $options = array(
            'user' => array(
                'title' => 'User <span class="cegg_required">*</span>',
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'required'),
                        'when' => 'is_active',
                        'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), 'App ID'),
                    ),
                ),
            ),
            'password' => array(
                'title' => 'Password <span class="cegg_required">*</span>',
                'callback' => array($this, 'render_password'),
                'default' => '',
                'validator' => array(
                    'trim',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'required'),
                        'when' => 'is_active',
                        'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), 'API Key'),
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
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
                        'arg' => 300,
                        'message' => sprintf(__('The field "%s" can not be more than %d.', 'content-egg'), 'Results', 300),
                    ),
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
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
                        'arg' => 10,
                        'message' => sprintf(__('The field "%s" can not be more than %d.', 'content-egg'), 'Results for updates', 10),
                    ),
                ),
            ),
            'sort' => array(
                'title' => __('Sort', 'content-egg'),
                'description' => __('Sort search results by the selected field.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    'auto' => 'auto',
                    'popularity' => 'popularity',
                    'price' => 'price',
                    'total_price' => 'total_price',
                    'score' => 'score',
                    'deal_score' => 'deal_score',
                    'deal_percent' => 'deal_percent',
                    'availability' => 'availability',
                    'rating' => 'rating',
                    'shop_review_count' => 'shop_review_count',
                    'shop_cpc' => 'shop_cpc',
                    'combined_grade' => 'combined_grade',
                ),
                'default' => 'auto',
            ),
            'sort_order' => array(
                'title' => __('Sort order', 'content-egg'),
                'description' => __('Choose the sort direction.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    'asc' => 'asc',
                    'desc' => 'desc',
                ),
                'default' => 'asc',
            ),
            'document_types' => array(
                'title' => __('Search result type', 'content-egg') . '**',
                'description' => __('Choose which Billiger.de result type should be searched first. Content Egg always converts matched products and base products into concrete shop offers, so the final saved results always contain offer-level price, merchant, and availability data.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    'product_offer' => __('Offers linked to products', 'content-egg') . ' [product_offer]',
                    'product' => __('Products only', 'content-egg') . ' [product]',
                    'offer' => __('Standalone offers only', 'content-egg') . ' [offer]',
                    'offer,product_offer' => __('All offers', 'content-egg') . ' [offer,product_offer]',
                ),
                'default' => 'offer,product_offer',
            ),
            'availability' => array(
                'title' => __('Availability', 'content-egg'),
                'description' => __('Limit results by availability.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    '' => __('Any', 'content-egg'),
                    '99' => __('Grey (99)', 'content-egg'),
                    '1' => __('Green (1)', 'content-egg'),
                    '2' => __('Yellow (2)', 'content-egg'),
                    '3' => __('Red (3)', 'content-egg'),
                ),
                'default' => '',
            ),
            'conditions' => array(
                'title' => __('Condition', 'content-egg'),
                'description' => __('Limit results by condition. By default, only new items are returned.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => array(
                    '1' => __('New', 'content-egg'),
                    '2' => __('Refurbished', 'content-egg'),
                    '3' => __('Used', 'content-egg'),
                ),
                'default' => '1',
            ),
            'free_reshipment' => array(
                'title' => __('Free reshipment', 'content-egg'),
                'description' => __('Limit results to items that can be returned free of charge.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'shops' => array(
                'title' => __('Shops', 'content-egg'),
                'description' => __('Limit results to comma-separated shop IDs.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'min_price' => array(
                'title' => __('Min price', 'content-egg'),
                'description' => __('Limit results to offers with at least this price.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'max_price' => array(
                'title' => __('Max price', 'content-egg'),
                'description' => __('Limit results to offers up to this price.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'is_deal' => array(
                'title' => __('Deals only', 'content-egg'),
                'description' => __('Limit results to products marked as deals.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'deal_longterm_bestprice' => array(
                'title' => __('Lowest yearly price', 'content-egg'),
                'description' => __('Limit results to items whose current price is the lowest within a year.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
            'sale_discount' => array(
                'title' => __('Sale discount', 'content-egg'),
                'description' => __('Limit results to offers on sale by discount percent.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
            ),
            'has_image' => array(
                'title' => __('Has image', 'content-egg'),
                'description' => __('Limit results to items that have an image.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => true,
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
