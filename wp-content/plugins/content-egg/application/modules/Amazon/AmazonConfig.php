<?php

namespace ContentEgg\application\modules\Amazon;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModuleConfig;
use ContentEgg\application\libs\amazon\AmazonLocales;

/**
 * AmazonConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class AmazonConfig extends AffiliateParserModuleConfig
{

	public function options()
	{
		$options = array(
			'api_mode' => array(
				'title'            => __('API mode', 'content-egg') . ' <span class="cegg_required">*</span>',
				'description'      => implode('<br>', array(
					__('Creators API is the recommended integration for Amazon going forward.', 'content-egg'),
					__('Auto mode will try Creators API first (if configured) and fall back to PA-API 5.0.', 'content-egg'),
					__('PA-API 5.0 is deprecated and should be used only if you cannot migrate yet.', 'content-egg'),
				)),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					'auto'     => __('Auto', 'content-egg'),
					'creators' => __('Creators API', 'content-egg'),
					'paapi5'   => __('PA-API 5.0 (deprecated)', 'content-egg'),
				),
				'default'          => 'auto',
				'section'          => 'default',
			),
			'creators_credential_id' => array(
				'title'       => __('Credential ID (Creators API) — Primary', 'content-egg'),
				'description' => implode('<br>', array(
					__('Creators API Credential ID. Create it in Associates Central → Tools → Creators API.', 'content-egg'),
					__('Used for any marketplace region that does not have its own credential set below. A single Primary credential is all you need for a single-region setup.', 'content-egg'),
				)),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'section'     => 'default',
			),
			'creators_credential_secret' => array(
				'title'       => __('Credential Secret (Creators API) — Primary', 'content-egg'),
				'description' => __('Creators API Credential Secret for the Primary credential above.', 'content-egg'),
				'callback'    => array($this, 'render_password'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'section'     => 'default',
			),
			'creators_credential_version' => array(
				'title'            => __('Credential Version (Creators API)', 'content-egg') . '**',
				'description'      => __('Auto-detects based on Credential ID format. Override only if auto-detection fails.', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					'auto' => __('Auto', 'content-egg'),
					'v2'   => __('v2.x (Cognito)', 'content-egg'),
					'v3'   => __('v3.x (LwA)', 'content-egg'),
				),
				'default'          => 'auto',
				'section'          => 'default',
			),
			// --- Deprecated PA-API 5.0 options (kept for backward compatibility) ---
			'access_key_id' => array(
				'title'       => __('Access Key (PA-API 5.0) — deprecated', 'content-egg') . '',
				'description' => implode('<br>', array(
					__('Deprecated: PA-API 5.0 credentials will be phased out. Please migrate to Creators API.', 'content-egg'),
					sprintf(
						__('For information about getting an Access Key, see <a target="_blank" href="%s">Register for Product Advertising API</a>.', 'content-egg'),
						'https://webservices.amazon.com/paapi5/documentation/register-for-pa-api.html'
					),
				)),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'required'),
						'when'    => array('is_active' => 1, 'api_mode' => 'paapi5'),
						'message' => __('The "Access Key" can not be empty.', 'content-egg'),
					),
				),
				'section'     => 'default',
			),
			'secret_access_key' => array(
				'title'       => __('Secret Key (PA-API 5.0) — deprecated', 'content-egg') . '',
				'description' => implode('<br>', array(
					__('Deprecated: PA-API 5.0 credentials will be phased out. Please migrate to Creators API.', 'content-egg'),
					sprintf(
						__('A key that is used in conjunction with the Access Key ID to cryptographically sign an API request. To retrieve your Secret Access Key, refer to <a target="_blank" href="%s">Register for Product Advertising API</a>.', 'content-egg'),
						'https://webservices.amazon.com/paapi5/documentation/register-for-pa-api.html'
					),
				)),
				'callback'    => array($this, 'render_password'),
				'default'     => '',
				'validator'   => array(
					'trim',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'required'),
						'when'    => array('is_active' => 1, 'api_mode' => 'paapi5'),
						'message' => __('The "Secret Key" can not be empty.', 'content-egg'),
					),
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'exact_length'),
						'arg'     => 40,
						'when'    => array('is_active' => 1, 'api_mode' => 'paapi5'),
						'message' => sprintf(__('The field "%s" must have an exact length of value: %d.', 'content-egg'), 'Secret Key', 40),
					),
				),
				'section'     => 'default',
			),
			'associate_tag' => array(
				'title'       => __('Associate Tag', 'content-egg') . ' <span class="cegg_required">*</span>',
				'description' => __('Your Associate/Partner Tag (applies to both Creators API and PA-API 5.0).', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'required'),
						'when'    => 'is_active',
						'message' => __('The "Associate Tag" can not be empty.', 'content-egg'),
					),
				),
				'section'     => 'default',
			),
			'locale'                  => array(
				'title'            => __('Default locale', 'content-egg') . '<span class="cegg_required">*</span>',
				'description'      => __('Your Amazon Associates tag works only in the locale in which you register. If you want to be an Amazon Associate in more than one locale, you must register separately for each locale.', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => self::getLocalesList(),
				'default'          => self::getDefaultLocale(),
				'section'          => 'default',
			),
			'price_mode' => array(
				'title'             => __('Price source', 'content-egg') . '**',
				'description' => implode('<br>', array(
					__('Choose which offer price to display.', 'content-egg'),
					__('“Buy Box” picks the current Buy Box winner.', 'content-egg'),
					__('“Prime exclusive” shows prices for Prime-only deals.', 'content-egg'),
					__('“All customers” shows prices available to everyone.', 'content-egg'),
				)),

				'callback'          => array($this, 'render_dropdown'),
				'dropdown_options'  => array(
					'buybox' => __('Buy Box', 'content-egg'),
					'prime'  => __('Prime exclusive', 'content-egg'),
					'all'    => __('All customers', 'content-egg'),
				),
				'default'           => 'buybox',
				'section'           => 'default',
			),
			'forced_urls_update'      => array(
				'title'       => __('Forced links update', 'content-egg'),
				'description' => __('Override/update existing links with new Tracking ID.', 'content-egg'),
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
				'section'     => 'default',
			),
			'entries_per_page'        => array(
				'title'       => __('Results', 'content-egg'),
				'description' => __('Specify the number of results to display for a single search query.', 'content-egg') . ' ' .
					__('It needs a bit more time to get more than 10 results in one request', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => 10,
				'validator'   => array(
					'trim',
					'absint',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
						'arg'     => 50,
						'message' => __('The field "Results" can not be more than 50.', 'content-egg'),
					),
				),
				'section'     => 'default',
			),
			'entries_per_page_update' => array(
				'title'       => __('Results for updates', 'content-egg'),
				'description' => __('Specify the number of results for automatic updates and autoblogging.', 'content-egg') . ' ' .
					__('It needs a bit more time to get more than 10 results in one request.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => 3,
				'validator'   => array(
					'trim',
					'absint',
					array(
						'call'    => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
						'arg'     => 50,
						'message' => __('The field "Results" can not be more than 50.', 'content-egg'),
					),
				),
				'section'     => 'default',
			),
			'link_type'               => array(
				'title'            => __('Link type', 'content-egg'),
				'description'      => __('Type of partner links. Know more about amazon <a target="_blank" href="https://affiliate-program.amazon.com/gp/associates/help/t2/a11">90 day cookie</a>.', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					'product'     => 'Product page',
					'add_to_cart' => 'Add to cart',
				),
				'default'          => 'product',
				'section'          => 'default',
			),
			'hide_prices' => array(
				'title' => __('Prices', 'content-egg') . '**',
				'description' => __('Amazon mandates that prices be updated at least every 24 hours if displayed on your site. Without API access, maintaining this update frequency is challenging, so consider hiding prices until you obtain API access. "Outdated prices" refers to updates that occurred more than 24 hours ago.', 'content-egg'),
				'callback' => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					'hide' => __('Hide prices', 'content-egg'),
					'hide_24' => __('Hide outdated prices', 'content-egg'),
					'display' => __('Display prices', 'content-egg'),
				),
				'default' => 'display',
			),
			'search_index'            => array(
				'title'       => __('Search Index', 'content-egg'),
				'description' => sprintf(__('Indicates the product category to search. SearchIndex values differ by marketplace. For list of search index values, refer <a target="_blank" href="%s">Locale Reference</a>.', 'content-egg'), 'https://affiliate-program.amazon.com/creatorsapi/docs/en-us/locale-reference#topics'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
			),
			'SortBy'                  => array(
				'title'            => __('Sort', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					''                   => __('Default (recommended)', 'content-egg'),
					'AvgCustomerReviews' => 'Avg Customer Reviews',
					'Featured'           => 'Featured',
					'NewestArrivals'     => 'Newest Arrivals',
					'Price:HighToLow'    => 'Price: High To Low',
					'Price:LowToHigh'    => 'Price: Low To High',
					'Relevance'          => 'Relevance',
				),
				'default'          => '',
			),
			'brouse_node'             => array(
				'title'       => __('Browse Node ID', 'content-egg'),
				'description' => __('A unique ID assigned by Amazon that identifies a product category/sub-category.', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
			),
			'title'                   => array(
				'title'       => __('Search in Title', 'content-egg'),
				'description' => __('Search for product titles only.', 'content-egg'),
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
				'section'     => 'default',
			),
			'Merchant'                => array(
				'title'            => __('Merchant', 'content-egg'),
				'description'      => __('Filters search results to return items having at least one offer sold by target merchant.', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					'All'    => __('List Offers from all Merchants', 'content-egg'),
					'Amazon' => __('List Offers only from Amazon', 'content-egg'),
				),
				'default'          => '',
			),
			'minimum_price'           => array(
				'title'       => __('Minimal price', 'content-egg'),
				'description' => __('Filters search results to items with at least one offer price above the specified value.', 'content-egg') . ' ' .
					__('For example, 31.41', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				//'metaboxInit' => true,
			),
			'maximum_price'           => array(
				'title'       => __('Maximal price', 'content-egg'),
				'description' => __('Filters search results to items with at least one offer price below the specified value.', 'content-egg') . ' ' .
					__('For example, 32.41', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				//'metaboxInit' => true,
			),
			'min_percentage_off'      => array(
				'title'            => __('Minimum saving percent', 'content-egg'),
				'description'      => __('Filters search results to items with at least one offer having saving percentage above the specified value.', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					''    => __('Any', 'content-egg'),
					'5%'  => '5%',
					'10%' => '10%',
					'15%' => '15%',
					'20%' => '20%',
					'25%' => '25%',
					'30%' => '30%',
					'35%' => '35%',
					'40%' => '40%',
					'45%' => '45%',
					'50%' => '50%',
					'60%' => '60%',
					'70%' => '70%',
					'80%' => '80%',
					'90%' => '90%',
					'95%' => '95%',
				),
				'default'          => '',
				//'metaboxInit' => true,
			),
			'Condition'               => array(
				'title'            => __('Condition', 'content-egg'),
				'description'      => __('The condition parameter filters offers by condition type. For example, Condition:Used will return items having atleast one offer of Used condition type.', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					''            => __('Any', 'content-egg'),
					'New'         => __('New', 'content-egg'),
					'Used'        => __('Used', 'content-egg'),
					'Collectible' => __('Collectible', 'content-egg'),
					'Refurbished' => __('Refurbished', 'content-egg'),
				),
				'default'          => '',
			),
			'CurrencyOfPreference'    => array(
				'title'       => __('Currency of preference', 'content-egg'),
				'description' => sprintf(__('Currency of preference in which the prices information should be returned in response. By default the prices are returned in the default currency of the marketplace. Expected currency code format is the ISO 4217 currency code (i.e. USD, EUR etc.). For information on default currency and valid currencies for a marketplace, refer <a target="_blank" href="%s">Locale Reference</a>.', 'content-egg'), 'https://affiliate-program.amazon.com/creatorsapi/docs/en-us/locale-reference#topics'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'default'     => '',
			),
			'LanguagesOfPreference'   => array(
				'title'       => __('Languages of preference', 'content-egg'),
				'description' => sprintf(__('Languages in order of preference in which the item information should be returned in response. By default the item information is returned in the default language of the marketplace. Expected locale format is the ISO 639 language code followed by underscore followed by the ISO 3166 country code (i.e. en_US, fr_CA etc.). For information on default language and valid languages for a marketplace, refer <a target="_blank" href="%s">Locale Reference</a>.', 'content-egg'), 'https://affiliate-program.amazon.com/creatorsapi/docs/en-us/locale-reference#topics'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
				'default'     => '',
			),
			'AmazonGlobal'            => array(
				'title'       => __('Amazon Global', 'content-egg'),
				'description' => __('Amazon Global products', 'content-egg')
					. ' <p class="description">' . __('A delivery program featuring international shipping to certain Exportable Countries.', 'content-egg') . '</p>',
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
			),
			'FreeShipping'            => array(
				'title'       => __('Free Shipping', 'content-egg'),
				'description' => __('Free Shipping products', 'content-egg')
					. ' <p class="description">' . __('A delivery program featuring free shipping of an item.', 'content-egg') . '</p>',
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
			),
			'FulfilledByAmazon'       => array(
				'title'       => __('Fulfilled by Amazon', 'content-egg'),
				'description' => __('Fulfilled by Amazon', 'content-egg')
					. ' <p class="description">' . __('Fulfilled by Amazon indicates that products are stored, packed and dispatched by Amazon.', 'content-egg') . '</p>',
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
			),
			'Prime'                   => array(
				'title'       => __('Prime', 'content-egg'),
				'description' => __('Prime products', 'content-egg')
					. ' <p class="description">' . __('An offer for an item which is eligible for Prime Program.', 'content-egg') . '</p>',
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
			),
			'MinReviewsRating'        => array(
				'title'            => __('Minimum rating', 'content-egg'),
				'description'      => __('Filters search results to items with customer review ratings above specified value.', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					''   => __('Any', 'content-egg'),
					'4.' => 4,
					'3.' => 3,
					'2.' => 2,
					'1.' => 1,
				),
				'default'          => '',
			),
			'save_img'                => array(
				'title'       => __('Save images', 'content-egg'),
				'description' => __('Save images to local server', 'content-egg')
					. ' <p class="description">' . __('Enabling this option may violate API rules.', 'content-egg') . '</p>',
				'callback'    => array($this, 'render_checkbox'),
				'default'     => false,
				'section'     => 'default',
			),
			'show_small_logos'        => array(
				'title'            => __('Small logos', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'description'      => __('Enabling this option may violate API rules.', 'content-egg') . ' '
					. sprintf(__('Read more: <a target="_blank" href="%s">Amazon brand usage guidelines</a>.', 'content-egg'), 'https://advertising.amazon.com/ad-specs/en/policy/brand-usage'),
				'dropdown_options' => array(
					'true'  => __('Show small logos', 'content-egg'),
					'false' => __('Hide small logos', 'content-egg'),
				),
				'default'          => 'false',
			),
			'show_large_logos'        => array(
				'title'            => __('Large logos', 'content-egg'),
				'callback'         => array($this, 'render_dropdown'),
				'dropdown_options' => array(
					'true'  => __('Show large logos', 'content-egg'),
					'false' => __('Hide large logos', 'content-egg'),
				),
				'default'          => 'true',
			),
		);

		foreach (self::getLocalesList() as $locale_id => $locale_name)
		{
			$options['associate_tag_' . $locale_id] = array(
				'title'       => sprintf(__('Associate Tag for %s locale', 'content-egg'), $locale_name),
				'description' => __('Type here your tracking ID for this locale if you need multiple locale parsing', 'content-egg'),
				'callback'    => array($this, 'render_input'),
				'default'     => '',
				'validator'   => array(
					'trim',
				),
			);
		}

		$parent = parent::options();
		$parent['ttl_items']['default'] = 86400;

		$options = array_merge($parent, $options);

		$options['forced_tag'] = array(
			'title'       => __('Forced Associate Tag', 'content-egg'),
			'description' => __('Forced replacement of tag parameter in all links. Usually you should leave this field blank!', 'content-egg'),
			'callback'    => array($this, 'render_input'),
			'default'     => '',
			'validator'   => array(
				'trim',
			),
		);

		$options['global_earning'] = array(
			'title'            => __('Global Earning', 'content-egg'),
			'description'      => __('Amazon redirects international shoppers to their local store. "Similar product" and "Search results" control what happens when the exact product is not available there: Amazon can offer a substitute, which may be from a different brand, or local search results. "No redirect" turns the redirect off entirely and keeps shoppers in this module\'s store, so you earn no international commissions.', 'content-egg')
				. ' ' . __('This is an experimental feature of the module.', 'content-egg'),
			'callback'         => array($this, 'render_dropdown'),
			'dropdown_options' => array(
				'local_matches'  => __('Similar product', 'content-egg'),
				'search_results' => __('Search results', 'content-egg'),
				'no_redirect'    => __('No redirect', 'content-egg'),
			),
			'default'          => 'local_matches',
		);

		// --- Per-region Creators API credentials (optional; for multi-region catalogs) ---
		// A Creators credential is scoped to one region group (NA/EU/FE). Fill these
		// only if your products span more than one region; each locale automatically
		// uses the credential for its region, falling back to the Primary credential.
		$options['creators_credential_id_na'] = array(
			'title'       => __('Credential ID — NA region', 'content-egg'),
			'description' => __('Optional. Creators API Credential ID for North America (US, CA, MX, BR). Leave blank to use the Primary credential.', 'content-egg'),
			'callback'    => array($this, 'render_input'),
			'default'     => '',
			'validator'   => array('trim'),
			'section'     => 'default',
		);
		$options['creators_credential_secret_na'] = array(
			'title'       => __('Credential Secret — NA region', 'content-egg'),
			'description' => __('Optional. Creators API Credential Secret for North America (US, CA, MX, BR).', 'content-egg'),
			'callback'    => array($this, 'render_password'),
			'default'     => '',
			'validator'   => array('trim'),
			'section'     => 'default',
		);
		$options['creators_credential_id_eu'] = array(
			'title'       => __('Credential ID — EU region', 'content-egg'),
			'description' => __('Optional. Creators API Credential ID for Europe (UK, DE, FR, IT, ES, NL, IE, BE, SE, PL, TR, AE, EG, SA, IN). Leave blank to use the Primary credential.', 'content-egg'),
			'callback'    => array($this, 'render_input'),
			'default'     => '',
			'validator'   => array('trim'),
			'section'     => 'default',
		);
		$options['creators_credential_secret_eu'] = array(
			'title'       => __('Credential Secret — EU region', 'content-egg'),
			'description' => __('Optional. Creators API Credential Secret for Europe.', 'content-egg'),
			'callback'    => array($this, 'render_password'),
			'default'     => '',
			'validator'   => array('trim'),
			'section'     => 'default',
		);
		$options['creators_credential_id_fe'] = array(
			'title'       => __('Credential ID — FE region', 'content-egg'),
			'description' => __('Optional. Creators API Credential ID for the Far East (JP, AU, SG). Leave blank to use the Primary credential.', 'content-egg'),
			'callback'    => array($this, 'render_input'),
			'default'     => '',
			'validator'   => array('trim'),
			'section'     => 'default',
		);
		$options['creators_credential_secret_fe'] = array(
			'title'       => __('Credential Secret — FE region', 'content-egg'),
			'description' => __('Optional. Creators API Credential Secret for the Far East (JP, AU, SG).', 'content-egg'),
			'callback'    => array($this, 'render_password'),
			'default'     => '',
			'validator'   => array('trim'),
			'section'     => 'default',
		);

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

	public function getActiveLocalesList()
	{
		$locales = self::getLocalesList();
		$active  = array();

		$default = $this->option('locale');
		$active[$default] = $locales[$default];

		foreach ($locales as $locale => $name)
		{
			if ($locale == $default)
			{
				continue;
			}
			if ($this->option('associate_tag_' . $locale))
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
}
