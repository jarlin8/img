<?php

namespace ContentEgg\application\modules\LomadeeProducts;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModuleConfig;

class LomadeeProductsConfig extends AffiliateParserModuleConfig
{
	public function options()
	{
		$options = array(
			'api_key' => array(
				'title'       => __('API Token', 'content-egg') . '<span class="cegg_required">*</span>',
				'description' => __('Paste your Lomadee API token.', 'content-egg'),
				'callback'    => array($this, 'render_password'),
				'default'     => '',
				'validator'   => array(
					'trim',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'required'),
						'when'    => 'is_active',
						'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), __('Lomadee API key', 'content-egg')),
					),
				),
			),
			'subid' => array(
				'title'       => __('Sub ID', 'content-egg') . '**',
				'description' => sprintf(
					__('Optional tracking ID added to Lomadee affiliate links. You can use a fixed value or dynamic placeholders (e.g., {{post_id}}, {{item_unique_id}}) to generate values automatically. <a href="%s" target="_blank">See placeholder guide</a>.', 'content-egg'),
					'https://ce-docs.keywordrush.com/features/subid-tracking'
				),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
			),

			'entries_per_page' => array(
				'title'       => __('Results', 'content-egg'),
				'description' => __('Number of products to fetch for a search.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => 10,
				'validator'   => array(
					'trim',
					'absint',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
						'arg'     => 100,
						'message' => sprintf(__('The field "%s" can not be more than %d.', 'content-egg'), __('Results', 'content-egg'), 100),
					),
				),
			),

			'entries_per_page_update' => array(
				'title'       => __('Results for updates', 'content-egg'),
				'description' => __('Number of products to fetch for automatic updates and autoblogging.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => 6,
				'validator'   => array(
					'trim',
					'absint',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
						'arg'     => 100,
						'message' => sprintf(__('The field "%s" can not be more than %d.', 'content-egg'), __('Results for updates', 'content-egg'), 100),
					),
				),
			),

			'min_price' => array(
				'title'       => __('Minimum price', 'content-egg'),
				'description' => __('Show products with price equal to or above this value. Leave empty for no minimum.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'metaboxInit' => true,
			),

			'max_price' => array(
				'title'       => __('Maximum price', 'content-egg'),
				'description' => __('Show products with price equal to or below this value. Leave empty for no maximum.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'metaboxInit' => true,
			),

			'organizationIds' => array(
				'title'       => __('Advertiser (Organization) IDs', 'content-egg') . '**',
				'description' => __('Optional. Show products only from specific advertisers. Enter one or more Organization IDs (UUIDs) separated by commas. If left empty, the search may include products from any advertiser (including those that require approval for affiliate links).', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'metaboxInit' => true,
			),

			'isAvailable' => array(
				'title'       => __('Only in-stock products', 'content-egg'),
				'description' => __('Exclude unavailable products from the search results', 'content-egg'),
				'callback'    => array($this, 'render_checkbox'),
				'default'     => true,
			),

			'save_img' => array(
				'title'       => __('Save images', 'content-egg'),
				'description' => __('Save images on server', 'content-egg'),
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
				'section'     => 'default',
			),
		);

		$options = array_merge(parent::options(), $options);
		return self::moveRequiredUp($options);
	}
}
