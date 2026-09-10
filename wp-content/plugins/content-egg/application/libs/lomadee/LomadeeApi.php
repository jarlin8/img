<?php

namespace ContentEgg\application\libs\lomadee;

defined('\ABSPATH') || exit;

use ContentEgg\application\libs\RestClient;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'RestClient.php';

class LomadeeApi extends RestClient
{
	protected static $timeout = 60; // sec

	// Docs examples currently use api-beta
	const API_URI_BASE = 'https://api-beta.lomadee.com.br';

	protected $apiKey;

	protected $_responseTypes = array(
		'json',
	);

	public function __construct($apiKey)
	{
		$this->apiKey = trim((string) $apiKey);

		$this->setUri(self::API_URI_BASE);
		$this->setResponseType('json');
	}

	/**
	 * Products list/search
	 * @link: https://docs.lomadee.com.br/api-reference/affiliate/products/all
	 */
	public function products($keywords = '', array $options = array())
	{
		$query = $this->normalizeProductsQuery($keywords, $options);
		$response = $this->restGet('/affiliate/products', $query);

		return $this->_decodeResponse($response);
	}

	/**
	 * Keep only supported query parameters and build price range if needed.
	 * Prices are sent in cents (min:max). If only one side is provided, the other
	 * side is filled with a sensible default.
	 *
	 */
	protected function normalizeProductsQuery($keywords, array $options)
	{
		$query = array();

		// supported params: page, limit, search, price, organizationIds, isAvailable
		if (!empty($options['page']))
		{
			$query['page'] = max(1, (int) $options['page']);
		}

		if (!empty($options['limit']))
		{
			$query['limit'] = max(1, (int) $options['limit']);
		}

		// keyword search (use explicit search if provided, otherwise use $keywords)
		$search = '';
		if (isset($options['search']) && $options['search'] !== '')
		{
			$search = (string) $options['search'];
		}
		elseif ($keywords !== null && $keywords !== '')
		{
			$search = (string) $keywords;
		}

		if ($search !== '')
		{
			$query['search'] = $search;
		}

		// organizationIds (comma-separated)
		if (!empty($options['organizationIds']))
		{
			$query['organizationIds'] = trim((string) $options['organizationIds']);
		}

		// availability ("true"/"false" as strings)
		if (isset($options['isAvailable']))
		{
			$val = $options['isAvailable'];

			$bool = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
			if ($bool === null)
			{
				$bool = false;
			}

			$query['isAvailable'] = $bool ? 'true' : 'false';
		}

		/**
		 * Price defaults (in cents)
		 * - min: R$0,01 => 1
		 * - max: R$1.000.000,00 => 100000000
		 */
		$defaultMinCents = 1;
		$defaultMaxCents = 100000000;

		/**
		 * Helper: parse BRL-like input ("74", "74.00", "74,00", "1.234,56") to cents int.
		 * Returns int|null
		 */
		$toCents = function ($value)
		{
			$value = trim((string) $value);
			if ($value === '')
			{
				return null;
			}

			// Remove spaces and currency symbols, keep only digits and separators
			$value = preg_replace('/[^\d\,\.]/', '', $value);

			// If both separators exist, assume "." is thousands and "," is decimals (pt-BR)
			if (strpos($value, ',') !== false && strpos($value, '.') !== false)
			{
				$value = str_replace('.', '', $value);
				$value = str_replace(',', '.', $value);
			}
			// If only comma exists, treat it as decimal separator
			elseif (strpos($value, ',') !== false)
			{
				$value = str_replace(',', '.', $value);
			}

			if (!is_numeric($value))
			{
				return null;
			}

			// Convert to cents, rounded to avoid float artifacts
			$cents = (int) round(((float) $value) * 100);

			return max(0, $cents);
		};

		// price can be passed directly ("min:max") OR as min_price/max_price
		if (!empty($options['price']))
		{
			$price = trim((string) $options['price']);

			if (strpos($price, ':') !== false)
			{
				$parts = explode(':', $price, 2);
				$minRaw = isset($parts[0]) ? $parts[0] : '';
				$maxRaw = isset($parts[1]) ? $parts[1] : '';

				$minCents = $toCents($minRaw);
				$maxCents = $toCents($maxRaw);

				if ($minCents === null && $maxCents === null)
				{
					return $query;
				}

				if ($minCents === null && $maxCents !== null)
				{
					$minCents = $defaultMinCents;
				}
				elseif ($maxCents === null && $minCents !== null)
				{
					$maxCents = $defaultMaxCents;
				}

				if ($maxCents >= $minCents)
				{
					$query['price'] = $minCents . ':' . $maxCents;
				}
			}
		}
		else
		{
			$minRaw = isset($options['min_price']) ? $options['min_price'] : '';
			$maxRaw = isset($options['max_price']) ? $options['max_price'] : '';

			$minCents = $toCents($minRaw);
			$maxCents = $toCents($maxRaw);

			if ($minCents === null && $maxCents === null)
			{
				return $query;
			}

			if ($minCents === null && $maxCents !== null)
			{
				$minCents = $defaultMinCents;
			}
			elseif ($maxCents === null && $minCents !== null)
			{
				$maxCents = $defaultMaxCents;
			}

			if ($maxCents >= $minCents)
			{
				$query['price'] = $minCents . ':' . $maxCents;
			}
		}

		return $query;
	}

	/**
	 * Generate affiliate (short) URL for a product page URL.
	 * @link: https://docs.lomadee.com.br/api-reference/affiliate/shorten/create
	 *
	 * @param string      $url
	 * @param string|null $organizationId
	 * @param string      $mdasc Optional extra identifier for tracking.
	 *
	 * @return string|false
	 * @throws \Exception On API error (code 429 for rate-limit).
	 */
	public function generateAffiliateLink($url, $organizationId, $mdasc = '')
	{
		$url = trim((string) $url);
		$organizationId = trim((string) $organizationId);

		if ($url === '' || $organizationId === '')
		{
			return false;
		}

		$endpoint = rtrim(self::API_URI_BASE, '/') . '/affiliate/shortener/url';

		$body = array(
			'type'           => 'Custom',
			'url'            => $url,
			'organizationId' => $organizationId,
		);

		$mdasc = trim((string) $mdasc);
		if ($mdasc !== '')
		{
			$body['mdasc'] = $mdasc;
		}

		$response = wp_remote_post($endpoint, array(
			'timeout' => static::$timeout,
			'headers' => array(
				'x-api-key'    => $this->apiKey,
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			),
			'body' => wp_json_encode($body),
		));

		if (is_wp_error($response))
		{
			throw new \Exception(esc_html($response->get_error_message()), 0);
		}

		$status = (int) wp_remote_retrieve_response_code($response);
		$raw    = (string) wp_remote_retrieve_body($response);
		$data   = json_decode($raw, true);

		// Rate limit / throttling
		if ($status === 429)
		{
			throw new \Exception('Rate limit exceeded.', 429);
		}

		// Handle explicit HTTP errors
		if ($status >= 400)
		{
			$message = 'Request failed.';
			if (is_array($data) && !empty($data['message']))
			{
				$message = (string) $data['message'];
			}
			throw new \Exception(esc_html($message), (int)$status);
		}

		/**
		 * Some "soft errors" come back with 200 and an array of channel objects,
		 * but shortUrls is empty and a message is provided.
		 *
		 * Example:
		 * [ { shortUrls: [], message: "Organization approval is required to generate links." } ]
		 */
		if (is_array($data) && isset($data[0]) && is_array($data[0]))
		{
			$first = $data[0];

			if (
				isset($first['shortUrls']) && is_array($first['shortUrls']) && empty($first['shortUrls']) &&
				!empty($first['message'])
			)
			{
				$msg = (string) $first['message'];

				// Special case: approval required (treat as blocked, let caller stop retrying)
				if (stripos($msg, 'approval') !== false)
				{
					throw new \Exception(esc_html($msg), 403);
				}

				// Other message with no URL: treat as failure
				throw new \Exception(esc_html($msg), 0);
			}
		}

		// Expected response is an ARRAY of channel objects, each may contain shortUrls[]
		// Example: [ { id, name, availableChannel: {...}, shortUrls: ["https://..."] } ]
		if (is_array($data))
		{
			// Case 1: API returns a plain list (most common)
			if (isset($data[0]) && is_array($data[0]))
			{
				foreach ($data as $channel)
				{
					if (!empty($channel['shortUrls']) && is_array($channel['shortUrls']) && !empty($channel['shortUrls'][0]))
					{
						return (string) $channel['shortUrls'][0];
					}
				}
			}

			// Case 2: API returns wrapped format (fallback)
			if (!empty($data['type']) && is_array($data['type']))
			{
				foreach ($data['type'] as $channel)
				{
					if (!empty($channel['shortUrls']) && is_array($channel['shortUrls']) && !empty($channel['shortUrls'][0]))
					{
						return (string) $channel['shortUrls'][0];
					}
				}
			}
		}

		return false;
	}

	public function restGet($path, ?array $query = null)
	{
		$this->setCustomHeaders(array('x-api-key' => $this->apiKey));
		return parent::restGet($path, $query);
	}
}
