<?php

namespace ContentEgg\application\modules\Impactradius;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModuleConfig;

/**
 * ImpactradiusConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 *
 */
class ImpactradiusConfig extends AffiliateParserModuleConfig
{

	public function options()
	{
		$options = array(
			'AccountSid' => array(
				'title'       => 'Account SID <span class="cegg_required">*</span>',
				'description' => __(
					'Your Impact.com Web Services Account SID. You can generate it in your Impact account settings: '
						. '<a href="https://app.impact.com/secure/mediapartner/api/en/api-access-tokens-ui.ihtml" target="_blank">Generate Account SID</a>.',
					'content-egg'
				),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'required'),
						'when'    => 'is_active',
						'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), 'Account SID'),
					),
				),
			),

			'AuthToken' => array(
				'title'       => 'Auth Token <span class="cegg_required">*</span>',
				'description' => __(
					'Your Impact.com Web Services Auth Token. You can generate it in your Impact account settings: '
						. '<a href="https://app.impact.com/secure/mediapartner/api/en/api-access-tokens-ui.ihtml" target="_blank">Generate Auth Token</a>.',
					'content-egg'
				),
				'callback'    => array($this, 'render_password'),
				'default'     => '',
				'validator'   => array(
					'trim',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'required'),
						'when'    => 'is_active',
						'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), 'Auth Token'),
					),
				),
			),

			'entries_per_page'        => array(
				'title'       => __('Results', 'content-egg'),
				'description' => __('Specify the number of results to display for a single search query.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => 20,
				'validator'   => array(
					'trim',
					'absint',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
						'arg'     => 200,
						'message' => sprintf(__('The field "%s" can not be more than %d.', 'content-egg'), 'Results', 200),
					),
				),
			),
			'entries_per_page_update' => array(
				'title'       => __('Results for Updates and Autoblogging', 'content-egg'),
				'description' => __('Specify the number of results for automatic updates and autoblogging.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => 6,
				'validator'   => array(
					'trim',
					'absint',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
						'arg'     => 200,
						'message' => sprintf(__('The field "%s" can not be more than %d.', 'content-egg'), 'Results', 200),
					),
				),
			),
			'minprice'                => array(
				'title'       => __('Minimal price', 'content-egg'),
				'description' => '',
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'metaboxInit' => true,
			),
			'maxprice'                => array(
				'title'       => __('Maximal price', 'content-egg'),
				'description' => '',
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'metaboxInit' => true,
			),
			'in_stock' => array(
				'title'       => __('In Stock Only', 'content-egg'),
				'description' => __(
					'Show only products that are marked as in stock by the advertiser.',
					'content-egg'
				) .
					'<p class="description">' . __(
						'Some advertisers do not provide stock information. If you receive few or no results, try disabling this filter.',
						'content-egg'
					) . '</p>',
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
				'section'     => 'default',
			),

			'save_img' => array(
				'title'       => __('Save Images', 'content-egg'),
				'description' => __('Download and store product images locally', 'content-egg'),
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
				'section'     => 'default',
			),
			'description_size' => array(
				'title'       => __('Description length', 'content-egg'),
				'description' => __('Maximum number of characters to keep in the product description. Set to 0 to keep the full description without trimming.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '300',
				'validator'   => array(
					'trim',
					'absint',
				),
				'section'     => 'default',
			),
			'CatalogId' => array(
				'title'       => __('Catalog IDs', 'content-egg'),
				'description' => sprintf(
					__('Filter search results by one or more catalog IDs. Enter multiple IDs separated by commas. Leave empty to search across all catalogs.<br><a href="%s" target="_blank">View your Catalog IDs</a>.', 'content-egg'),
					'https://app.impact.com/secure/mediapartner/ads/view-product-catalogs-flow.ihtml'
				),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array('trim'),
			),
			'CampaignId'      => array(
				'title'       => __('Campaign IDs', 'content-egg'),
				'description' => __('Filter search results by one or more campaign (program) IDs. Enter multiple IDs separated by commas. Leave empty to include all campaigns.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array('trim'),
			),

			'subId1' => array(
				'title'       => __('Sub ID 1', 'content-egg'),
				'description' => sprintf(
					__('Optional Impact SubId1 used to track conversions by page, campaign, or channel. Enter a fixed value or use dynamic placeholders (e.g., {{post_id}}, {{post_slug}}, {{item_sku}}) to set it per link automatically. Max 255 characters. <a target="_blank" href="%s">Placeholder guide</a>.', 'content-egg'),
					'https://ce-docs.keywordrush.com/features/subid-tracking'
				),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array('trim'),
				'section'     => 'default',
			),
			'subId2' => array(
				'title'       => __('Sub ID 2', 'content-egg'),
				'description' => __('Optional Impact SubId2. Fixed value or dynamic placeholders. Max 255 characters.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array('trim'),
				'section'     => 'default',
			),
			'subId3' => array(
				'title'       => __('Sub ID 3', 'content-egg'),
				'description' => __('Optional Impact SubId3. Fixed value or dynamic placeholders. Max 255 characters.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array('trim'),
				'section'     => 'default',
			),

		);

		$options = array_merge(parent::options(), $options);
		return self::moveRequiredUp($options);
	}
}
