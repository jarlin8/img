<?php

namespace ContentEgg\application\modules\Billigerde;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\libs\solutenetwork\SoluteApi;

use function ContentEgg\prnx;

/**
 * BilligerdeModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class BilligerdeModule extends AffiliateParserModule
{

    public function info()
    {
        return array(
            'name' => 'Billigerde',
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/billigerde-module',
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
        $productIdFromUrl = $this->extractProductIdFromUrl($keyword);
        $baseProductIdFromUrl = $this->extractBaseProductIdFromUrl($keyword);
        $directProductId = preg_match('/^\d+$/', trim((string) $keyword)) ? trim((string) $keyword) : '';

        if (TextHelper::isEan($keyword))
        {
            $keyword = 'gtin:' . $keyword;
            $directProductId = '';
            $productIdFromUrl = '';
            $baseProductIdFromUrl = '';
        }

        $limit = (int) ($is_autoupdate
            ? $this->config('entries_per_page_update')
            : $this->config('entries_per_page'));

        $options = array();

        if ($limit > 0 && $limit <= 300)
        {
            $options['limit'] = $limit;
        }

        $options['document_types'] = (string) $this->config('document_types') ?: 'product_offer';
        $filters = array();

        $sort = (string) $this->config('sort');
        if ($sort !== '')
        {
            if ($sort !== 'auto')
            {
                $sortOrder = (string) $this->config('sort_order');
                if ($sortOrder === 'desc')
                {
                    $sort = '-' . $sort;
                }
                elseif ($sortOrder === 'asc')
                {
                    $sort = '+' . $sort;
                }
            }

            $options['sort'] = $sort;
        }

        $availability = (string) $this->config('availability');
        if ($availability !== '')
        {
            $filters['availability'] = wp_json_encode(array((int) $availability));
        }

        $conditions = (string) $this->config('conditions');
        if ($conditions !== '')
        {
            $filters['conditions'] = wp_json_encode(array((int) $conditions));
        }

        if ($this->config('free_reshipment'))
        {
            $filters['free_reshipment'] = '[1]';
        }

        $shops = trim((string) $this->config('shops'));
        if ($shops !== '')
        {
            $shopIds = TextHelper::getArrayFromCommaList($shops);
            if ($shopIds)
            {
                $filters['shops'] = wp_json_encode($shopIds);
            }
        }

        $minPrice = isset($query_params['min_price'])
            ? trim((string) $query_params['min_price'])
            : trim((string) $this->config('min_price'));
        $maxPrice = isset($query_params['max_price'])
            ? trim((string) $query_params['max_price'])
            : trim((string) $this->config('max_price'));
        if ($minPrice !== '' || $maxPrice !== '')
        {
            $filters['price'] = $minPrice . '..' . $maxPrice;
        }

        if ($this->config('is_deal'))
        {
            $filters['is_deal'] = '[1]';
        }

        if ($this->config('deal_longterm_bestprice'))
        {
            $filters['deal_longterm_bestprice'] = '[1]';
        }

        $saleDiscount = trim((string) $this->config('sale_discount'));
        if ($saleDiscount !== '')
        {
            $filters['sale_discount'] = $saleDiscount;
        }

        if ($this->config('has_image'))
        {
            $filters['has_image'] = '[1]';
        }

        if ($filters)
        {
            $options['filters'] = $filters;
        }

        if ($baseProductIdFromUrl !== '')
        {
            // Resolve baseproduct URL to products, then expand them to offers.
            $items = $this->getOffersByBaseProductId($baseProductIdFromUrl, $options);
        }
        elseif ($productIdFromUrl !== '' || $directProductId !== '')
        {
            // Product URL can be resolved directly to offers.
            $productId = $productIdFromUrl !== '' ? $productIdFromUrl : $directProductId;
            $offerOptions = array();
            if (isset($options['sort']))
            {
                $offerOptions['sort'] = $options['sort'];
            }

            $items = $this->extractItems($this->getApiClient()->getOffersByProductIds(array($productId), $offerOptions));
        }
        else
        {
            $results = $this->getApiClient()->search($keyword, $options);

            if (
                !$results
                || !isset($results['items'])
                || !is_array($results['items'])
            )
            {
                return array();
            }

            $items = $this->expandProductsToOffers($results['items'], $options);
        }

        return $this->prepareResults($items);
    }

    public function doRequestItems(array $items): array
    {
        $offerIds = array();
        foreach ($items as $item)
        {
            if (!empty($item['unique_id']))
            {
                $offerIds[] = (string) $item['unique_id'];
            }
        }

        $offerIds = array_values(array_unique(array_filter($offerIds)));
        if (!$offerIds)
        {
            return $items;
        }

        $results = $this->getApiClient()->getOffersByIds($offerIds);
        if (!is_array($results))
        {
            throw new \Exception('doRequestItems request error.');
        }

        $offers = $this->extractItems($results);

        $preparedOffers = $this->prepareResults($offers);
        $offersById = array();
        foreach ($preparedOffers as $offer)
        {
            $offersById[(string) $offer->unique_id] = $offer;
        }

        foreach ($items as $key => $item)
        {
            $offerId = !empty($item['unique_id']) ? (string) $item['unique_id'] : '';
            if ($offerId === '' || empty($offersById[$offerId]))
            {
                $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                $items[$key]['price'] = 0;
                $items[$key]['priceOld'] = 0;
                continue;
            }

            $offer = $offersById[$offerId];
            $items[$key]['stock_status'] = $offer->stock_status;
            $items[$key]['price'] = $offer->price;
            $items[$key]['priceOld'] = $offer->priceOld;
            $items[$key]['currencyCode'] = $offer->currencyCode;
            $items[$key]['currency'] = $offer->currency;
            $items[$key]['shipping_cost'] = $offer->shipping_cost;
            $items[$key]['availability'] = $offer->availability;
            $items[$key]['url'] = $offer->url;
            $items[$key]['domain'] = $offer->domain;
        }

        return $items;
    }

    private function getOffersByBaseProductId(string $baseProductId, array $options): array
    {
        // Resolve baseproduct via its cheapest linked product.
        $baseProducts = $this->extractItems($this->getApiClient()->getBaseProductsByIds($baseProductId, array(
            'properties' => 'cheapest_product',
        )));
        if (!$baseProducts)
        {
            return array();
        }

        $productId = '';
        foreach ($baseProducts as $baseProduct)
        {
            if (!empty($baseProduct['cheapest_product']['id']))
            {
                $productId = (string) $baseProduct['cheapest_product']['id'];
                break;
            }
        }

        if ($productId === '')
        {
            return array();
        }

        $offerOptions = array();
        if (isset($options['sort']))
        {
            $offerOptions['sort'] = $options['sort'];
        }

        return $this->extractItems($this->getApiClient()->getOffersByProductIds(array($productId), $offerOptions));
    }

    private function expandProductsToOffers(array $items, array $options): array
    {
        // Expand product hits into concrete offers.
        $productIds = array();
        $offers = array();
        foreach ($items as $item)
        {
            if (!is_array($item))
            {
                continue;
            }

            if (($item['type'] ?? '') === 'product' && !empty($item['id']))
            {
                $productIds[] = (string) $item['id'];
                continue;
            }

            $offers[] = $item;
        }

        $productIds = array_values(array_unique(array_filter($productIds)));
        if ($productIds)
        {
            $offerOptions = array();
            if (isset($options['sort']))
            {
                $offerOptions['sort'] = $options['sort'];
            }

            $productOfferResults = $this->getApiClient()->getOffersByProductIds($productIds, $offerOptions);
            foreach ($this->extractItems($productOfferResults) as $productOffer)
            {
                if (is_array($productOffer))
                {
                    $offers[] = $productOffer;
                }
            }
        }

        // Keep only unique offers before final slicing.
        $items = array();
        $offerIds = array();
        foreach ($offers as $offer)
        {
            $offerId = isset($offer['id']) ? (string) $offer['id'] : '';
            if ($offerId !== '')
            {
                if (isset($offerIds[$offerId]))
                {
                    continue;
                }

                $offerIds[$offerId] = true;
            }

            $items[] = $offer;
        }

        return $items;
    }

    private function extractItems($results): array
    {
        if (!is_array($results))
        {
            return array();
        }

        if (isset($results['items']) && is_array($results['items']))
        {
            return $results['items'];
        }

        if (isset($results['data']) && is_array($results['data']))
        {
            return $results['data'];
        }

        return $results;
    }

    private function extractProductIdFromUrl($url): string
    {
        if (!is_string($url) || $url === '')
        {
            return '';
        }

        if (preg_match('~billiger\.de/products/(\d+)~i', $url, $matches))
        {
            return $matches[1];
        }

        return '';
    }

    private function extractBaseProductIdFromUrl($url): string
    {
        if (!is_string($url) || $url === '')
        {
            return '';
        }

        if (preg_match('~billiger\.de/baseproducts/(\d+)~i', $url, $matches))
        {
            return $matches[1];
        }

        return '';
    }

    private function prepareResults($results)
    {
        $data = array();
        foreach ($results as $r)
        {
            $data[] = $this->prepareResult($r);
        }

        return $data;
    }

    private function prepareResult($r)
    {
        $content = new ContentProduct;
        $content->unique_id = $r['id'] ?? '';
        $content->title = $r['name'] ?? '';
        $content->description = $r['description'] ?? '';
        $content->url = $r['clickout_url'] ?? '';
        $content->img = $r['main_image'] ?? '';
        $content->promo = $r['promo_text'] ?? '';
        $content->manufacturer = $r['brand']['name'] ?? '';
        $content->merchant = $r['shop']['name'] ?? '';
        $content->logo = $r['shop']['logo_url'] ?? '';
        $content->domain = preg_replace('/^www\./i', '', (string) (parse_url($r['shop']['url'] ?? '', PHP_URL_HOST) ?: 'billiger.de'));
        $content->category = $r['category']['description'] ?? '';
        $content->availability = $r['shipping']['text'] ?? '';

        if (!empty($r['category']['path']) && is_array($r['category']['path']))
        {
            foreach ($r['category']['path'] as $pathItem)
            {
                if (!empty($pathItem['description']))
                {
                    $content->categoryPath[] = $pathItem['description'];
                }
            }
        }

        if (!$content->categoryPath && !empty($r['category']['description']))
        {
            $content->categoryPath[] = $r['category']['description'];
        }

        $price = $r['price']['amount'] ?? null;
        if ($price !== null && $price !== '')
        {
            $content->price = (float) $price / 100;
        }

        $totalPrice = $r['price']['total'] ?? null;
        if ($totalPrice !== null && $totalPrice !== '')
        {
            $content->price = (float) $totalPrice / 100;
        }

        $shippingCost = $r['price']['shipping'] ?? ($r['shipping']['price']['amount'] ?? null);
        if ($shippingCost !== null && $shippingCost !== '')
        {
            $content->shipping_cost = (float) $shippingCost / 100;
        }

        $oldPrice = $r['price_info']['old_price']['amount'] ?? null;
        if ($oldPrice !== null && $oldPrice !== '')
        {
            $content->priceOld = (float) $oldPrice;
        }

        $currencyCode = $r['price']['currency'] ?? ($r['price_info']['price']['currency'] ?? '');
        $content->currencyCode = $currencyCode;
        $content->currency = $currencyCode ? TextHelper::currencyTyping($currencyCode) : '';

        $shippingCode = $r['shipping']['code'] ?? '';
        if ($shippingCode === 'red')
        {
            $content->stock_status = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
        }
        elseif ($shippingCode === 'green' || $shippingCode === 'yellow')
        {
            $content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
        }
        else
        {
            $content->stock_status = ContentProduct::STOCK_STATUS_UNKNOWN;
        }

        if (!empty($r['images']) && is_array($r['images']))
        {
            foreach ($r['images'] as $image)
            {
                if (!empty($image['url']))
                {
                    $content->images[] = $image['url'];
                }
            }
        }

        if (!$content->img && !empty($content->images))
        {
            $content->img = reset($content->images);
        }

        if (!empty($r['external_product_ids']['gtin'][0]))
        {
            $content->ean = $r['external_product_ids']['gtin'][0];
        }

        if (!empty($r['external_product_ids']['mpn'][0]))
        {
            $content->sku = $r['external_product_ids']['mpn'][0];
        }

        if (!empty($r['properties']) && is_array($r['properties']))
        {
            foreach ($r['properties'] as $property)
            {
                if (empty($property['description']) || empty($property['values']) || !is_array($property['values']))
                {
                    continue;
                }

                $values = array();
                foreach ($property['values'] as $value)
                {
                    if (!empty($value['description']))
                    {
                        $values[] = $value['description'];
                    }
                }

                if ($values)
                {
                    $feature = array(
                        'name' => $property['description'],
                        'value' => implode(', ', $values),
                    );

                    if (!empty($property['display_group']))
                    {
                        $feature['group'] = $property['display_group'];
                    }

                    $content->features[] = $feature;
                }
            }
        }

        $content->extra = new ExtraDataBilligerde();
        ExtraDataBilligerde::fillAttributes($content->extra, $r);
        return $content;
    }

    private function getApiClient()
    {
        return new SoluteApi($this->config('user'), $this->config('password'));
    }

    public function renderResults()
    {
        PluginAdmin::render('_metabox_results', array('module_id' => $this->getId()));
    }

    public function renderSearchPanel()
    {
        $this->render('search_panel', array('module_id' => $this->getId()));
    }

    public function renderSearchResults()
    {
        PluginAdmin::render('_metabox_search_results', array('module_id' => $this->getId()));
    }

    public function renderUpdatePanel()
    {
        $this->render('update_panel', array('module_id' => $this->getId()));
    }

    public static function getPriceParamMap()
    {
        return [
            'min' => 'min_price',
            'max' => 'max_price',
        ];
    }
}
