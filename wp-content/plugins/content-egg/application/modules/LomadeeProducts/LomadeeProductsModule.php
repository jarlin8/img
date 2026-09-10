<?php

namespace ContentEgg\application\modules\LomadeeProducts;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\components\ContentManager;
use ContentEgg\application\components\LinkHandler;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\libs\lomadee\LomadeeApi;

class LomadeeProductsModule extends AffiliateParserModule
{
	private $api_client = null;

	public function info()
	{
		add_action('admin_notices', array($this, 'maybeShowApprovalNotice'));

		return array(
			'name' => 'Lomadee Products',
		);
	}

	public static function getPriceParamMap()
	{
		return array(
			'min' => 'min_price',
			'max' => 'max_price',
		);
	}

	/**
	 * Show admin notice when some advertisers require approval for link generation.
	 */
	public function maybeShowApprovalNotice()
	{
		if (!is_admin())
		{
			return;
		}

		if (!get_transient('cegg_lomadee_approval_notice'))
		{
			return;
		}

		$message = __(
			'CE:Lomadee: Some affiliate links could not be generated because advertiser approval is required. Please request/await approval in your Lomadee account. Content Egg will automatically try again every 24 hours.',
			'content-egg'
		);

		$domains = get_transient('cegg_lomadee_approval_domains');
		if (is_array($domains) && !empty($domains))
		{
			$domains = array_slice(array_values(array_unique($domains)), -20);
			$message .= ' ' . sprintf(
				__('Advertisers: %s', 'content-egg'),
				implode(', ', array_map('sanitize_text_field', $domains))
			);
		}

		echo '<div class="notice notice-warning"><p>' . esc_html($message) . '</p></div>';
	}

	public function isDeprecated()
	{
		return false;
	}

	public function releaseVersion()
	{
		return '18.14.0';
	}

	public function getParserType()
	{
		return self::PARSER_TYPE_PRODUCT;
	}

	public function defaultTemplateName()
	{
		return 'grid';
	}

	public function isItemsUpdateAvailable()
	{
		return false;
	}

	public function doRequest($keyword, $query_params = array(), $is_autoupdate = false)
	{
		$options = array();

		$limit = $is_autoupdate ? $this->config('entries_per_page_update') : $this->config('entries_per_page');
		$options['limit'] = (int) $limit;

		// Optional filters from config
		$organizationIds = $this->config('organizationIds');
		if ($organizationIds !== '')
		{
			$organizationIds = TextHelper::commaList($organizationIds);
			$options['organizationIds'] = $organizationIds;
		}

		$min_price = $this->config('min_price');
		if ($min_price !== '')
		{
			$options['min_price'] = $min_price;
		}

		$max_price = $this->config('max_price');
		if ($max_price !== '')
		{
			$options['max_price'] = $max_price;
		}

		$options['isAvailable'] = (bool) $this->config('isAvailable');

		// Allow overrides from $query_params (metabox)
		foreach (array('min_price', 'max_price') as $p)
		{
			if (isset($query_params[$p]) && $query_params[$p] !== '' && $query_params[$p] !== null)
			{
				$options[$p] = $query_params[$p];
			}
		}

		$results = $this->getApiClient()->products($keyword, $options);

		if (!isset($results['data']) || !is_array($results['data']))
		{
			return array();
		}

		return $this->prepareResults($results['data']);
	}

	private function prepareResults($results)
	{
		$data = array();

		foreach ($results as $p)
		{
			$content = new ContentProduct;

			$opt = $this->getPrimaryOption($p);

			$product_id = isset($p['id']) ? (string) $p['id'] : '';
			$mongo_id   = isset($p['_id']) ? (string) $p['_id'] : '';
			$seller     = !empty($opt['seller']) ? trim((string) $opt['seller']) : '';

			// Unique ID: stable product id + seller (if any), fallback to _id
			$uid_base = $product_id !== '' ? $product_id : $mongo_id;
			$content->unique_id = $uid_base . ($seller !== '' ? '-' . $seller : '');

			// Title / Description
			$content->title       = !empty($p['name']) ? (string) $p['name'] : '';
			$content->description = !empty($p['description']) ? (string) $p['description'] : '';

			// URL
			$content->orig_url = !empty($p['url']) ? (string) $p['url'] : '';
			$content->url = !empty($p['url']) ? (string) $p['url'] : '';

			$content->domain = TextHelper::getHostName($content->orig_url);

			// Images (primary + gallery)
			$content->img = $this->getPrimaryImageUrl($p, $opt);

			$images = $this->collectImageUrls($p, $opt);
			if ($images)
			{
				$content->images = $images;
			}

			// Price / Old price (from first option pricing)
			$price    = $this->getOptionPrice($opt, 'price');
			$listPrice = $this->getOptionPrice($opt, 'listPrice');

			if ($price !== null)
			{
				$content->price = $price;
			}

			if ($listPrice !== null && $price !== null && $listPrice > $price)
			{
				$content->priceOld = $listPrice;
			}

			// Currency
			$content->currencyCode = 'BRL';

			// Merchant
			$content->merchant = $seller;
			if (!$content->merchant && !empty($content->domain))
			{
				$content->merchant = $content->domain;
			}

			// EAN
			if (!empty($opt['ean']))
			{
				$content->ean = (string) $opt['ean'];
			}

			// Category (best effort)
			if (!empty($p['categories']) && is_array($p['categories']))
			{
				$names = array();
				foreach ($p['categories'] as $cat)
				{
					if (!empty($cat['name']))
					{
						$names[] = (string) $cat['name'];
					}
				}
				if ($names)
				{
					$content->category = $names[0];
					$content->categoryPath = $names;
				}
			}

			// Stock / availability
			$available = null;
			if (isset($p['available']))
			{
				$available = (bool) $p['available'];
			}

			$stock_value = null;
			if (isset($opt['stocks'][0]['value']))
			{
				$stock_value = (int) $opt['stocks'][0]['value'];
			}

			if ($available === false)
			{
				$content->stock_status = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
			}
			else
			{
				$content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
			}

			// Extra fields
			$content->extra = new ExtraDataLomadeeProducts();
			$content->extra->product_id      = $product_id;
			$content->extra->product_mongo_id = $mongo_id;
			$content->extra->organizationId  = !empty($p['organizationId']) ? (string) $p['organizationId'] : '';
			$content->extra->createdAt       = !empty($p['createdAt']) ? (string) $p['createdAt'] : '';
			$content->extra->updatedAt       = !empty($p['updatedAt']) ? (string) $p['updatedAt'] : '';
			$content->extra->lastUpdate      = !empty($p['lastUpdate']) ? (string) $p['lastUpdate'] : '';

			$content->extra->seller          = $seller;
			$content->extra->stock_value     = $stock_value;
			$content->extra->option_id       = !empty($opt['id']) ? (string) $opt['id'] : '';

			$data[] = $content;
		}

		return $data;
	}

	private function getPrimaryOption(array $product)
	{
		if (!empty($product['options']) && is_array($product['options']))
		{
			// Prefer first available option
			foreach ($product['options'] as $opt)
			{
				if (isset($opt['available']) && $opt['available'])
				{
					return $opt;
				}
			}
			// Otherwise first option
			return reset($product['options']);
		}

		return array();
	}

	private function getOptionPrice(array $opt, $field)
	{
		if (!empty($opt['pricing']) && is_array($opt['pricing']) && !empty($opt['pricing'][0]) && isset($opt['pricing'][0][$field]))
		{
			return (float) $opt['pricing'][0][$field];
		}
		return null;
	}

	private function getPrimaryImageUrl(array $product, array $opt)
	{
		if (!empty($opt['images'][0]['url']))
		{
			return (string) $opt['images'][0]['url'];
		}
		if (!empty($product['images'][0]['url']))
		{
			return (string) $product['images'][0]['url'];
		}
		return '';
	}

	private function collectImageUrls(array $product, array $opt)
	{
		$urls = array();

		foreach (array($opt, $product) as $src)
		{
			if (!empty($src['images']) && is_array($src['images']))
			{
				foreach ($src['images'] as $img)
				{
					if (!empty($img['url']))
					{
						$urls[] = (string) $img['url'];
					}
				}
			}
		}

		$urls = array_values(array_unique(array_filter($urls)));
		return $urls;
	}

	public function viewDataPrepare($data)
	{

		if (empty($data) || !is_array($data))
		{
			return $data;
		}

		// Need a valid post ID to save generated affiliate links back to DB
		global $post;
		$post_id = (!empty($post) && !empty($post->ID)) ? (int) $post->ID : 0;

		if ($post_id <= 0)
		{
			return $data;
		}

		if (is_admin())
		{
			$default_limit   = 3;
		}
		else
		{
			$default_limit   = 5;
		}
		$max_to_generate = (int) apply_filters('cegg_lomadee_max_affiliate_links_per_request', $default_limit);
		if ($max_to_generate < 0)
		{
			$max_to_generate = 0;
		}

		$generated_count = 0;
		$changed         = false;

		$subid = (string)$this->config('subid');

		foreach ($data as $key => $d)
		{
			if ($generated_count >= $max_to_generate)
			{
				break;
			}

			$orig_url = isset($d['orig_url']) ? trim((string) $d['orig_url']) : '';
			$url      = isset($d['url']) ? trim((string) $d['url']) : '';

			if ($orig_url === '' || $url === '')
			{
				continue;
			}

			// Generate only if we still have the direct product URL
			if ($orig_url !== $url)
			{
				continue;
			}

			$organizationId = isset($d['extra']['organizationId']) ? trim((string) $d['extra']['organizationId']) : '';
			if ($organizationId === '')
			{
				continue;
			}

			// Skip orgs that require approval (avoid repeated API calls)
			$block_key = 'cegg_lomadee_org_block_' . md5($organizationId);
			if (get_transient($block_key))
			{
				continue;
			}

			// Resolve dynamic placeholders
			$mdasc = (string) LinkHandler::getUrlTemplate($orig_url, $subid, $d);

			try
			{
				$affiliate_url = $this->getApiClient()->generateAffiliateLink($orig_url, $organizationId, $mdasc);
				$generated_count++;

				if ($affiliate_url)
				{
					$data[$key]['url'] = $affiliate_url;
					$changed = true;
				}
			}
			catch (\Exception $e)
			{
				$msg  = strtolower((string) $e->getMessage());
				$code = (int) $e->getCode();

				$domain = '';
				if (!empty($d['domain']))
				{
					$domain = (string) $d['domain'];
				}

				// Approval required -> block this organization for a while to avoid spam
				if ($code === 403 && strpos($msg, 'approval') !== false)
				{
					set_transient($block_key, 1, DAY_IN_SECONDS);

					$list_key = 'cegg_lomadee_approval_domains';
					$domains  = get_transient($list_key);
					if (!is_array($domains))
					{
						$domains = array();
					}

					if ($domain !== '' && !in_array($domain, $domains, true))
					{
						$domains[] = $domain;

						// cap list to last 20 domains
						if (count($domains) > 20)
						{
							$domains = array_slice($domains, -20);
						}

						set_transient($list_key, $domains, DAY_IN_SECONDS);
					}

					// Trigger admin notice flag
					set_transient('cegg_lomadee_approval_notice', 1, DAY_IN_SECONDS);

					continue;
				}

				// Domain not allowed -> block this domain for longer to avoid repeated failures
				if ($code === 400 && strpos($msg, 'domain_not_allowed') !== false)
				{
					set_transient($block_key, 1, WEEK_IN_SECONDS);
					continue;
				}

				// Rate limit / throttling: stop trying this time
				if (
					$code === 429 ||
					strpos($msg, '429') !== false ||
					strpos($msg, 'rate') !== false ||
					strpos($msg, 'limit') !== false ||
					strpos($msg, 'too many') !== false
				)
				{
					break;
				}

				continue;
			}
		}

		// Save once if something changed
		if ($changed)
		{
			$module_id = $this->getId();
			ContentManager::saveData($data, $module_id, $post_id);
		}

		return $data;
	}

	private function getApiClient()
	{
		if ($this->api_client === null)
		{
			$this->api_client = new LomadeeApi($this->config('api_key'));
		}

		return $this->api_client;
	}

	public function renderResults()
	{
		PluginAdmin::render('_metabox_results', array('module_id' => $this->getId()));
	}

	public function renderSearchResults()
	{
		PluginAdmin::render('_metabox_search_results', array('module_id' => $this->getId()));
	}

	public function renderSearchPanel()
	{
		$this->render('search_panel', array('module_id' => $this->getId()));
	}

	public function renderUpdatePanel()
	{
		$this->render('update_panel', array('module_id' => $this->getId()));
	}
}
