<?php

namespace ContentEgg\application\modules\Geizhalsde;

defined('\ABSPATH') || exit;

use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\libs\geizhals\GeizhalsApi;


/**
 * GeizhalsdeModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class GeizhalsdeModule extends AffiliateParserModule
{
    public function info()
    {
        return array(
            'name' => 'Geizhalsde',
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/geizhalsde-module',
        );
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
        return true;
    }

    public function isUrlSearchAllowed()
    {
        return true;
    }

    public function releaseVersion()
    {
        return '20.0.0';
    }

    public function doRequest($keyword, $query_params = array(), $is_autoupdate = false)
    {
        $queryType = 'free';
        $productIdFromUrl = $this->extractProductIdFromUrl($keyword);
        if ($productIdFromUrl !== '')
        {
            $keyword = $productIdFromUrl;
            $queryType = 'id';
        }
        elseif (TextHelper::isEan($keyword))
        {
            $queryType = 'gtin';
        }
        elseif (preg_match('/^\d+$/', trim((string) $keyword)))
        {
            $keyword = trim((string) $keyword);
            $queryType = 'id';
        }

        $limit = (int) ($is_autoupdate
            ? $this->config('entries_per_page_update')
            : $this->config('entries_per_page'));
        $params = $this->buildRequestParams($limit > 0 ? $limit : 10, $queryType);

        $results = $this->getApiClient()->searchProducts($keyword, $queryType, $params);
        if (
            !$results
            || !isset($results['response'])
            || !is_array($results['response'])
        )
        {
            return array();
        }

        return $this->prepareResults($results['response']);
    }

    public function doRequestItems(array $items): array
    {
        $itemsByProductId = array();
        foreach ($items as $key => $item)
        {
            $productId = $this->extractProductIdFromItem($item);
            if ($productId === '')
            {
                continue;
            }

            if (!isset($itemsByProductId[$productId]))
            {
                $itemsByProductId[$productId] = array();
            }

            $itemsByProductId[$productId][] = $key;
        }

        if (!$itemsByProductId)
        {
            return $items;
        }

        $params = $this->buildRequestParams(1, 'id');
        $productIds = array_keys($itemsByProductId);
        $results = $this->getApiClient()->bulkQueryProducts($productIds, $params, 'id');
        $productsById = $this->indexProductsById($results);

        foreach ($itemsByProductId as $productId => $keys)
        {
            $product = $productsById[$productId] ?? null;
            $offers = $product ? $this->getFilteredOffers($product) : array();

            foreach ($keys as $key)
            {
                if (!$product)
                {
                    $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                    $items[$key]['price'] = 0;
                    continue;
                }

                $offer = $this->findMatchingOffer($items[$key], $offers);
                if (!$offer)
                {
                    $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                    $items[$key]['price'] = 0;
                    continue;
                }

                $prepared = $this->prepareResult($product, $offer);
                $items[$key]['stock_status'] = $prepared->stock_status;
                $items[$key]['price'] = $prepared->price;
                $items[$key]['currencyCode'] = $prepared->currencyCode;
                $items[$key]['currency'] = $prepared->currency;
                $items[$key]['shipping_cost'] = $prepared->shipping_cost;
                $items[$key]['availability'] = $prepared->availability;
                $items[$key]['url'] = $prepared->url;
                $items[$key]['aff_url'] = $prepared->aff_url;
                $items[$key]['orig_url'] = $prepared->orig_url;
                $items[$key]['domain'] = $prepared->domain;
                $items[$key]['merchant'] = $prepared->merchant;
                $items[$key]['logo'] = $prepared->logo;
            }
        }

        return $items;
    }

    public function renderResults()
    {
        PluginAdmin::render('_metabox_results', array('module_id' => $this->getId()));
    }

    public function renderSearchResults()
    {
        PluginAdmin::render('_metabox_search_results', array('module_id' => $this->getId()));
    }

    public function renderUpdatePanel()
    {
        $this->render('update_panel', array('module_id' => $this->getId()));
    }

    private function getApiClient()
    {
        return new GeizhalsApi($this->config('secret_key'), $this->config('user'));
    }

    private function buildRequestParams(int $limit, string $queryType = 'free'): array
    {
        $params = array(
            'n_results' => $limit,
            'n_offers' => max(1, (int) $this->config('n_offers')),
            'add_asin' => true,
            'add_rank' => true,
            'add_ratings' => true,
            'amazon_prime' => true,
            'include_offer_ids' => true,
            'lang' => (string) $this->config('lang'),
            'loc' => (string) $this->config('loc'),
            'image_size' => (string) $this->config('image_size'),
            'merchant_details' => true,
            'omit_description' => (bool) $this->config('omit_description'),
            'omit_images' => (bool) $this->config('omit_images'),
            'redir_baseurl' => true,
            'search_ngrams_only' => (bool) $this->config('search_ngrams_only'),
            'structured' => true,
            'votes' => true,
            'pickup_only' => (bool) $this->config('pickup_only'),
        );

        if ($queryType === 'free')
        {
            $params['filter_accessories'] = (bool) $this->config('filter_accessories');
        }

        $campaignId = trim((string) $this->config('campaignID'));
        if ($campaignId !== '')
        {
            $params['campaignID'] = $campaignId;
        }

        return $params;
    }

    private function prepareResults(array $results): array
    {
        $data = array();
        foreach ($results as $product)
        {
            if (!is_array($product))
            {
                continue;
            }

            foreach ($this->getFilteredOffers($product) as $offer)
            {
                $data[] = $this->prepareResult($product, $offer);
            }
        }

        return $data;
    }

    private function indexProductsById($results): array
    {
        if (
            !$results
            || !isset($results['response'])
            || !is_array($results['response'])
        )
        {
            return array();
        }

        $indexed = array();
        foreach ($results['response'] as $product)
        {
            if (!is_array($product) || empty($product['gzhid']))
            {
                continue;
            }

            $indexed[(string) $product['gzhid']] = $product;
        }

        return $indexed;
    }

    private function getFilteredOffers(array $product): array
    {
        return $this->filterOffersByMerchantDomains($this->extractOffers($product));
    }

    private function filterOffersByMerchantDomains(array $offers): array
    {
        $allowedDomains = $this->getAllowedMerchantDomains();
        $excludeBestPriceLink = (bool) $this->config('exclude_geizhals_best_price_link');

        if (!$allowedDomains && !$excludeBestPriceLink)
        {
            return $offers;
        }

        if (!$allowedDomains)
        {
            $allowedDomains = array();
        }

        $filtered = array();
        foreach ($offers as $offer)
        {
            $domain = $this->extractOfferDomain($offer);

            if ($excludeBestPriceLink && $domain === 'geizhals.de')
            {
                continue;
            }

            if (!$allowedDomains)
            {
                $filtered[] = $offer;
                continue;
            }

            if ($domain !== '' && in_array($domain, $allowedDomains, true))
            {
                $filtered[] = $offer;
            }
        }

        return $filtered;
    }

    private function getAllowedMerchantDomains(): array
    {
        $raw = (string) $this->config('merchant_domains');
        if ($raw === '')
        {
            return array();
        }

        $domains = preg_split('/\s*,\s*/', trim($raw), -1, PREG_SPLIT_NO_EMPTY);
        if (!$domains)
        {
            return array();
        }

        $normalized = array();
        foreach ($domains as $domain)
        {
            $domain = $this->normalizeMerchantDomain($domain);
            if ($domain !== '')
            {
                $normalized[] = $domain;
            }
        }

        return array_values(array_unique($normalized));
    }

    private function extractOfferDomain(array $offer): string
    {
        $shop = $offer['shop'] ?? array();
        if (!empty($shop['clean_baseurl']))
        {
            return $this->normalizeMerchantDomain((string) $shop['clean_baseurl']);
        }

        if (!empty($shop['url']))
        {
            $host = (string) parse_url($shop['url'], PHP_URL_HOST);
            if ($host !== '')
            {
                return $this->normalizeMerchantDomain($host);
            }
        }

        if (!empty($offer['price']['url']))
        {
            $host = (string) parse_url($offer['price']['url'], PHP_URL_HOST);
            if ($host !== '')
            {
                return $this->normalizeMerchantDomain($host);
            }
        }

        return '';
    }

    private function normalizeMerchantDomain(string $domain): string
    {
        $domain = trim(strtolower($domain));
        if ($domain === '')
        {
            return '';
        }

        $domain = preg_replace('~^https?://~i', '', $domain);
        $domain = preg_replace('~^www\.~i', '', $domain);
        $domain = preg_replace('~/.*$~', '', $domain);

        return trim((string) $domain);
    }

    private function prepareResult(array $product, array $offer): ContentProduct
    {
        $content = new ContentProduct;
        $shop = $offer['shop'] ?? array();
        $offerUrl = !empty($offer['price']['url']) ? $offer['price']['url'] : ($product['bpoffer_link'] ?? '');
        $offerHash = $offerUrl ? md5($offerUrl) : md5(wp_json_encode($offer));
        $images = $this->normalizeImageUrls(
            !empty($product['images']) && is_array($product['images']) ? $product['images'] : array(),
            (string) $this->config('image_size')
        );

        $content->unique_id = !empty($offer['offer_id'])
            ? (string) $offer['offer_id']
            : (($product['gzhid'] ?? '') . '-' . $offerHash);
        $content->title = $product['product'] ?? '';
        $content->description = $offer['shop']['prod_desc'][1] ?? ($offer['shop']['prod_desc'][0] ?? '');
        $content->img = $images[0] ?? '';
        $content->images = $content->img ? array_values(array_filter($images, static function ($image) use ($content)
        {
            return $image !== $content->img;
        })) : $images;
        $content->img_large = $images[0] ?? '';
        $content->url = $offerUrl;
        $content->orig_url = $product['bpoffer_link'] ?? '';
        $content->price = isset($offer['price']['value']) ? (float) $offer['price']['value'] : 0.0;
        $content->currencyCode = $offer['price']['currency'] ?? ($offer['pricing']['loc_currency'] ?? 'EUR');
        $content->currency = TextHelper::currencyTyping($content->currencyCode);
        $content->manufacturer = $product['manufacturer_name'] ?? '';
        $content->merchant = $shop['merchant_name'] ?? ($shop['name'] ?? '');
        $content->logo = $shop['logo'] ?? '';
        $content->domain = preg_replace('/^www\./i', '', (string) (($shop['clean_baseurl'] ?? '') ?: parse_url((string) ($shop['url'] ?? ''), PHP_URL_HOST)));
        $content->category = '';
        if (!empty($product['category']) && is_array($product['category']))
        {
            $lastCategory = end($product['category']);
            if (!empty($lastCategory['label']))
            {
                $content->category = $lastCategory['label'];
            }
        }
        $content->rating = isset($product['rating_stars']) ? (float) $product['rating_stars'] : 0;
        $content->ratingDecimal = isset($product['rating_percent']) ? (float) $product['rating_percent'] : $content->rating;
        $content->reviewsCount = isset($product['rating_count']) ? (int) $product['rating_count'] : 0;
        $content->availability = $offer['avl']['descr'][0]['text'] ?? '';
        $content->ean = !empty($product['gtin'][0]) ? $product['gtin'][0] : '';
        $content->sku = (string) ($product['gzhid'] ?? '');
        $content->shipping_cost = $this->extractShippingCost($shop);
        $content->stock_status = $this->mapAvailability($offer);

        if (!empty($product['category']) && is_array($product['category']))
        {
            foreach ($product['category'] as $category)
            {
                if (!empty($category['label']))
                {
                    $content->categoryPath[] = $category['label'];
                }
            }
        }

        return $content;
    }

    private function extractOffers(array $product): array
    {
        if (!empty($product['offers']) && is_array($product['offers']))
        {
            return $product['offers'];
        }

        return array();
    }

    private function normalizeImageUrls(array $images, string $imageSize): array
    {
        $imageSize = in_array($imageSize, array('n', 's', 't'), true) ? $imageSize : 'n';

        foreach ($images as $key => $image)
        {
            if (!is_string($image))
            {
                unset($images[$key]);
                continue;
            }

            $images[$key] = preg_replace('~-[nst]\.(webp|jpg|jpeg|png)$~i', '-' . $imageSize . '.$1', $image);
        }

        return array_values($images);
    }

    private function extractFirstProduct($results): array
    {
        if (
            !is_array($results)
            || empty($results['response'])
            || !is_array($results['response'])
            || empty($results['response'][0])
            || !is_array($results['response'][0])
        )
        {
            return array();
        }

        return $results['response'][0];
    }

    private function extractProductIdFromItem(array $item): string
    {
        if (!empty($item['sku']))
        {
            return (string) $item['sku'];
        }

        if (!empty($item['unique_id']) && preg_match('/^(\d+)-/', (string) $item['unique_id'], $matches))
        {
            return $matches[1];
        }

        return '';
    }

    private function extractProductIdFromUrl($url): string
    {
        if (!is_string($url) || $url === '')
        {
            return '';
        }

        if (preg_match('~geizhals\.[a-z.]+/.+-a(\d+)\.html~i', $url, $matches))
        {
            return $matches[1];
        }

        return '';
    }

    private function findMatchingOffer(array $item, array $offers): array
    {
        if (!$offers)
        {
            return array();
        }

        $itemUniqueId = (string) ($item['unique_id'] ?? '');
        foreach ($offers as $offer)
        {
            if (!empty($offer['offer_id']) && $itemUniqueId !== '' && $itemUniqueId === (string) $offer['offer_id'])
            {
                return $offer;
            }
        }

        $itemUrl = (string) ($item['url'] ?? '');
        foreach ($offers as $offer)
        {
            if ($itemUrl !== '' && $itemUrl === (string) ($offer['price']['url'] ?? ''))
            {
                return $offer;
            }
        }

        $itemDomain = preg_replace('/^www\./i', '', (string) ($item['domain'] ?? ''));
        $itemMerchant = (string) ($item['merchant'] ?? '');
        foreach ($offers as $offer)
        {
            $shop = $offer['shop'] ?? array();
            $offerDomain = preg_replace('/^www\./i', '', (string) (($shop['clean_baseurl'] ?? '') ?: parse_url((string) ($shop['url'] ?? ''), PHP_URL_HOST)));
            $offerMerchant = (string) ($shop['merchant_name'] ?? ($shop['name'] ?? ''));

            if ($itemDomain !== '' && $itemMerchant !== '' && $itemDomain === $offerDomain && $itemMerchant === $offerMerchant)
            {
                return $offer;
            }
        }

        foreach ($offers as $offer)
        {
            $offerMerchant = (string) (($offer['shop']['merchant_name'] ?? '') ?: ($offer['shop']['name'] ?? ''));
            if ($itemMerchant !== '' && $itemMerchant === $offerMerchant)
            {
                return $offer;
            }
        }

        return count($offers) === 1 ? $offers[0] : array();
    }

    private function extractShippingCost(array $shop): ?float
    {
        if (empty($shop['shipping']) || !is_array($shop['shipping']))
        {
            return null;
        }

        foreach ($shop['shipping'] as $shippingByLocale)
        {
            if (!is_array($shippingByLocale))
            {
                continue;
            }

            foreach ($shippingByLocale as $shippingOption)
            {
                if (isset($shippingOption['shipping_price']))
                {
                    return (float) $shippingOption['shipping_price'];
                }
            }
        }

        return null;
    }

    private function mapAvailability(array $offer): int
    {
        $availabilityText = mb_strtolower((string) ($offer['avl']['descr'][0]['text'] ?? ''));
        if (
            $availabilityText !== ''
            && preg_match('/nicht|ausverkauft|derzeit\s+nicht|nicht\s+lieferbar|nicht\s+verf/u', $availabilityText)
        )
        {
            return ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
        }

        $bestCode = $offer['avl']['best_code'] ?? null;
        if (is_bool($bestCode))
        {
            $bestCode = (int) $bestCode;
        }
        elseif (is_numeric($bestCode))
        {
            $bestCode = (int) $bestCode;
        }

        if ($bestCode === 0 || $bestCode === 1 || $bestCode === 2)
        {
            return ContentProduct::STOCK_STATUS_IN_STOCK;
        }

        if ($availabilityText !== '')
        {
            return ContentProduct::STOCK_STATUS_IN_STOCK;
        }

        return ContentProduct::STOCK_STATUS_UNKNOWN;
    }
}
