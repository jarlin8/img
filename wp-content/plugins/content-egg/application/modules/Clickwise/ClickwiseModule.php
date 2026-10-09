<?php

namespace ContentEgg\application\modules\Clickwise;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\libs\clickwise\ClickwiseApi;

/**
 * ClickwiseModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ClickwiseModule extends AffiliateParserModule
{
    // API page size and lookup batch size.
    const MAX_LIMIT = 100;

    private $api_client = null;

    public function info()
    {
        return array(
            'name' => 'Clickwise',
            'description' => __('Searches GTIN products of Clickwise merchants with your tracked links.', 'content-egg'),
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/clickwise',
        );
    }

    public function releaseVersion()
    {
        return '22.1.0';
    }

    public function getParserType()
    {
        return self::PARSER_TYPE_PRODUCT;
    }

    public function defaultTemplateName()
    {
        return 'grid';
    }

    public function isGtinSearchAllowed()
    {
        return true;
    }

    public function isItemsUpdateAvailable()
    {
        return true;
    }

    public function doRequest($keyword, $query_params = array(), $is_autoupdate = false)
    {
        $limit = $is_autoupdate
            ? (int) $this->config('entries_per_page_update')
            : (int) $this->config('entries_per_page');
        $limit = max(1, min(self::MAX_LIMIT, $limit));

        $keyword = trim((string) $keyword);

        $options = array('limit' => $limit);
        if ($country = (string) $this->config('country'))
            $options['country'] = $country;
        if ($merchant = (int) $this->config('merchant'))
            $options['merchant'] = $merchant;

        if (self::isGtin($keyword))
        {
            $options['gtins'] = $keyword;
            $results = $this->getApiClient()->lookup($options);
        }
        else
        {
            // The API rejects shorter queries with a 400.
            if (mb_strlen($keyword) < 3)
                return array();

            $options['q'] = $keyword;

            // One result per group_id (model and color): the cheapest in-stock
            // size, with the sizes in stock listed.
            if ($this->config('group_variants'))
                $options['group'] = 1;

            $options = array_merge($options, $this->getPriceFilter($query_params));

            $results = $this->getApiClient()->search($options);
        }

        if (empty($results['products']) || !is_array($results['products']))
            return array();

        return $this->prepareResults($results['products']);
    }

    public function doRequestItems(array $items)
    {
        $ids = array();
        foreach ($items as $item)
        {
            if (!empty($item['unique_id']))
                $ids[] = (string) $item['unique_id'];
        }

        if (!$ids)
            return $items;

        // A failed chunk throws and aborts the whole update on purpose: catching
        // it would leave those ids missing below and mark live products out of
        // stock.
        $fresh = array();
        foreach (array_chunk(array_unique($ids), self::MAX_LIMIT) as $chunk)
        {
            $results = $this->getApiClient()->lookup(array(
                'ids' => implode(',', $chunk),
                'limit' => self::MAX_LIMIT,
            ));

            if (empty($results['products']) || !is_array($results['products']))
                continue;

            foreach ($this->prepareResults($results['products']) as $product)
                $fresh[$product->unique_id] = $product;
        }

        foreach ($items as $key => $item)
        {
            $unique_id = !empty($item['unique_id']) ? (string) $item['unique_id'] : '';

            // Clickwise drops products it no longer delivers from the response.
            if ($unique_id === '' || !isset($fresh[$unique_id]))
            {
                $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                continue;
            }

            $product = $fresh[$unique_id];

            $items[$key]['stock_status'] = $product->stock_status;
            $items[$key]['price'] = $product->price;
            $items[$key]['priceOld'] = $product->priceOld;
            $items[$key]['url'] = $product->url;
            $items[$key]['domain'] = $product->domain;
            $items[$key]['extra']['availability'] = $product->extra->availability;
            $items[$key]['extra']['updated_at'] = $product->extra->updated_at;
        }

        return $items;
    }

    /**
     * min_price/max_price from the search panel, else from the settings. The
     * API compares them with each offer's price in its own currency, so they
     * only make sense with a Country set. Search only: lookup ignores them.
     */
    private function getPriceFilter(array $query_params)
    {
        $min = self::priceParam(isset($query_params['priceFrom']) && $query_params['priceFrom'] !== '' ? $query_params['priceFrom'] : $this->config('priceFrom'));
        $max = self::priceParam(isset($query_params['priceTo']) && $query_params['priceTo'] !== '' ? $query_params['priceTo'] : $this->config('priceTo'));

        // min above max is a 400; read it as a swapped range.
        if ($min !== null && $max !== null && $min > $max)
            list($min, $max) = array($max, $min);

        $filter = array();
        if ($min !== null)
            $filter['min_price'] = $min;
        if ($max !== null)
            $filter['max_price'] = $max;

        return $filter;
    }

    private static function priceParam($value)
    {
        $value = str_replace(',', '.', trim((string) $value));
        if ($value === '' || !is_numeric($value) || (float) $value < 0)
            return null;

        return round((float) $value, 4);
    }

    public static function getPriceParamMap()
    {
        return array(
            'min' => 'priceFrom',
            'max' => 'priceTo',
        );
    }

    private function prepareResults(array $products)
    {
        $data = array();
        foreach ($products as $r)
        {
            if (empty($r['id']) || empty($r['url']))
                continue;

            $data[] = $this->prepareResult($r);
        }

        return $data;
    }

    private function prepareResult(array $r)
    {
        $content = new ContentProduct;

        $content->unique_id = (string) $r['id'];
        $content->title = isset($r['title']) ? (string) $r['title'] : '';
        $content->url = $r['url'];
        $content->img = isset($r['image']) ? (string) $r['image'] : '';
        $content->currencyCode = isset($r['currency']) ? (string) $r['currency'] : '';

        // Prices are decimal strings such as "154.9500".
        $content->price = isset($r['price']) ? (float) $r['price'] : 0;
        $regular = isset($r['regular_price']) ? (float) $r['regular_price'] : 0;
        $content->priceOld = ($regular > $content->price) ? $regular : 0;

        if (!empty($r['description']))
            $content->description = (string) $r['description'];
        if (!empty($r['brand']))
            $content->manufacturer = (string) $r['brand'];
        if (!empty($r['category']))
        {
            $content->category = (string) $r['category'];
            $content->categoryPath = array_map('trim', explode('>', $content->category));
        }
        if (!empty($r['gtin']))
            $content->ean = (string) $r['gtin'];

        $content->stock_status = self::stockStatus(isset($r['availability']) ? $r['availability'] : '');

        $merchant = isset($r['merchant']) && is_array($r['merchant']) ? $r['merchant'] : array();
        if (!empty($merchant['name']))
            $content->merchant = (string) $merchant['name'];
        $content->domain = self::getMerchantDomain($merchant);

        $content->extra = new ExtraDataClickwise();
        $content->extra->country = isset($r['country']) ? (string) $r['country'] : '';
        $content->extra->merchant_id = !empty($merchant['id']) ? (int) $merchant['id'] : 0;
        $content->extra->group_id = isset($r['group_id']) ? (string) $r['group_id'] : '';
        $content->extra->availability = isset($r['availability']) ? (string) $r['availability'] : '';
        $content->extra->size = isset($r['size']) ? (string) $r['size'] : '';
        $content->extra->color = isset($r['color']) ? (string) $r['color'] : '';
        $content->extra->updated_at = isset($r['updated_at']) ? (string) $r['updated_at'] : '';

        // Only present with group=1.
        if (!empty($r['sizes']) && is_array($r['sizes']))
            $content->extra->sizes = implode(' / ', self::sortSizes($r['sizes']));

        return $content;
    }

    /**
     * Numeric sizes ("40", "40,5") sort by value; anything else (S, M, L)
     * keeps the API order, which an alphabetical sort would scramble.
     */
    private static function sortSizes(array $sizes)
    {
        $sizes = array_map('strval', $sizes);

        foreach ($sizes as $size)
        {
            if (!preg_match('/^\d+([.,]\d+)?$/', $size))
                return $sizes;
        }

        usort($sizes, function ($a, $b) {
            return (float) str_replace(',', '.', $a) <=> (float) str_replace(',', '.', $b);
        });

        return $sizes;
    }

    /**
     * merchant.domain is null when Clickwise has no domain for the merchant.
     * The domain must never be left empty: the base class would then derive
     * one from the image URL (often a CDN such as cdn.shopify.com) and show
     * that as the store name. A mapping can be added through the
     * cegg_clickwise_merchant2domain filter; otherwise the merchant name
     * stands in.
     */
    private static function getMerchantDomain(array $merchant)
    {
        if (!empty($merchant['domain']))
            return (string) $merchant['domain'];

        $name = isset($merchant['name']) ? (string) $merchant['name'] : '';
        $map = (array) \apply_filters('cegg_clickwise_merchant2domain', array(), $name);

        if ($name !== '' && !empty($map[$name]))
            return (string) $map[$name];

        return $name;
    }

    private static function isGtin($keyword)
    {
        return ctype_digit($keyword) && in_array(strlen($keyword), array(8, 12, 13, 14), true);
    }

    /**
     * Pre-order and backorder products can still be bought.
     */
    private static function stockStatus($availability)
    {
        switch ($availability)
        {
            case 'in_stock':
            case 'preorder':
            case 'backorder':
                return ContentProduct::STOCK_STATUS_IN_STOCK;
            case 'out_of_stock':
                return ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
            default:
                return ContentProduct::STOCK_STATUS_UNKNOWN;
        }
    }

    private function getApiClient()
    {
        if ($this->api_client === null)
            $this->api_client = new ClickwiseApi($this->config('apiKey'));

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
