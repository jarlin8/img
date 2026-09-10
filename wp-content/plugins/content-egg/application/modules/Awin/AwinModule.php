<?php

namespace ContentEgg\application\modules\Awin;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateFeedParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\components\LinkHandler;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\helpers\ProductHelper;
use ContentEgg\application\modules\Awin\models\AwinProductModel;;

/**
 * AwinModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class AwinModule extends AffiliateFeedParserModule
{
    /**
     * How many EAN matches to inspect when re-linking an offer to a new aw_product_id.
     * The feed's ean index is not merchant-scoped, so a widely carried product returns
     * roughly one row per shop and the merchant filter runs afterwards, in PHP.
     */
    const EAN_RELINK_CANDIDATES = 50;

    public function isGtinSearchAllowed()
    {
        return true;
    }


    public function info()
    {
        return array(
            'name' => 'Awin',
            'description' => sprintf(__('Adds products from %s.', 'content-egg'), '<a href="http://www.keywordrush.com/go/awin">AWIN</a>'),
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/awin',
        );
    }

    public static function getMerchantDomainPairs()
    {
        $pairs = array('Cdiscount FR' => 'cdiscount.com', 'Darty FR' => 'darty.com', 'SANICARE DE' => 'sanicare.de');

        return \apply_filters('cegg_awin_merchant_mapping', $pairs);
    }

    public function releaseVersion()
    {
        return '5.2.0';
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

    public function getProductModel()
    {
        return AwinProductModel::model();
    }

    public function isZippedFeed()
    {
        return true;
    }

    public function isUrlSearchAllowed()
    {
        return true;
    }

    public function getFeedUrl()
    {
        if (!$feed_url = $this->buildFeedUrl())
        {
            throw new \Exception('Wrong format of Datafeed URL.');
        }

        return $feed_url;
    }

    /**
     * Pick the category value to use from an AWIN feed row.
     *
     * AWIN's own taxonomy field is empty for many merchants, while the
     * merchant's own ">"-delimited path is filled. category_name is still tried
     * first so feeds that do populate it keep creating the categories they
     * create today.
     */
    private static function resolveCategoryValue(array $r): string
    {
        $fields = apply_filters('cegg_awin_category_fields', array('category_name', 'merchant_category'), $r);

        foreach ((array) $fields as $field)
        {
            $value = trim((string) ($r[$field] ?? ''));

            if ($value !== '')
            {
                return $value;
            }
        }

        return '';
    }

    protected function feedProductPrepare(array $data)
    {
        $product = array();
        $product['id'] = $data['aw_product_id'];
        if (!(int) $data['in_stock'])
        {
            $product['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
        }
        else
        {
            $product['stock_status'] = ContentProduct::STOCK_STATUS_IN_STOCK;
        }
        $product['price'] = (float) $data['search_price'];
        $product['title'] = \sanitize_text_field($data['product_name']);
        $product['orig_url'] = $data['merchant_deep_link'];
        $product['ean'] = '';

        if ($data['ean'])
        {
            $ean = TextHelper::fixEan($data['ean']);
            if (TextHelper::isEan($ean))
                $product['ean'] = $ean;
        }

        if ($data['product_GTIN'])
        {
            $ean = TextHelper::fixEan($data['product_GTIN']);
            if (TextHelper::isEan($ean))
                $product['ean'] = $ean;
        }

        $product['category'] = '';
        if ($category = self::resolveCategoryValue($data))
        {
            $product['category'] = mb_substr(sanitize_text_field($category), 0, 191);
        }

        $product['brand'] = '';
        if (!empty($data['brand_name']))
        {
            $product['brand'] = mb_substr(sanitize_text_field((string) $data['brand_name']), 0, 191);
        }

        $product['product'] = serialize($data);

        return $product;
    }

    public function doRequest($keyword, $query_params = array(), $is_autoupdate = false)
    {
        $this->maybeScheduleImport();

        if ($is_autoupdate)
        {
            $limit = $this->config('entries_per_page_update');
        }
        else
        {
            $limit = $this->config('entries_per_page');
        }

        if (TextHelper::isEan($keyword))
        {
            $results = $this->product_model->searchByEan(TextHelper::fixEan($keyword), $limit);
        }
        elseif (filter_var($keyword, FILTER_VALIDATE_URL))
        {
            $tracking = self::resolveAwinTrackingUrl($keyword);

            if ($tracking && $tracking['type'] === 'id')
            {
                // pclick.php link: 'p' is the exact aw_product_id, so look it up directly instead of matching by URL.
                $product = $this->product_model->searchById($tracking['value']);
                $results = $product ? array($product) : array();
            }
            else
            {
                if ($tracking && $tracking['type'] === 'url')
                {
                    // cread.php link: 'p' is the url-encoded merchant deep link.
                    $keyword = $tracking['value'];
                }

                $results = $this->product_model->searchByUrl($keyword, $this->config('partial_url_match'), $limit, $this->config('ignore_query_string'));
            }
        }
        else
        {
            $options = array();
            if (!empty($query_params['price_min']))
            {
                $options['price_min'] = (float) $query_params['price_min'];
            }
            if (!empty($query_params['price_max']))
            {
                $options['price_max'] = (float) $query_params['price_max'];
            }

            $results = $this->product_model->searchByKeyword($keyword, $limit, $options);
        }

        if (!$results)
        {
            return array();
        }

        return $this->prepareResults($results);
    }

    /**
     * Recognizes AWIN's own tracking-link formats (pclick.php / cread.php) and extracts
     * the underlying aw_product_id or merchant deep link, so a resolved affiliate link
     * (e.g. from a ThirstyAffiliates destination) can still be matched to a feed product.
     */
    private static function resolveAwinTrackingUrl($url)
    {
        $host = wp_parse_url($url, PHP_URL_HOST);
        if (!$host || stripos($host, 'awin1.com') === false)
        {
            return null;
        }

        $path = (string) wp_parse_url($url, PHP_URL_PATH);
        parse_str((string) wp_parse_url($url, PHP_URL_QUERY), $query);

        if (stripos($path, 'pclick.php') !== false && !empty($query['p']) && ctype_digit((string) $query['p']))
        {
            return array('type' => 'id', 'value' => $query['p']);
        }

        if (stripos($path, 'cread.php') !== false && !empty($query['p']))
        {
            return array('type' => 'url', 'value' => $query['p']);
        }

        return null;
    }

    private function prepareResults($results)
    {
        $data = array();
        foreach ($results as $key => $product)
        {
            if (!$r = unserialize($product['product']))
            {
                continue;
            }

            $content = new ContentProduct;
            $content->unique_id = $r['aw_product_id'];
            $content->title = $r['product_name'];
            $content->url = $r['aw_deep_link'];
            $content->orig_url = $r['merchant_deep_link'];
            $content->img = $r['merchant_image_url'];
            $content->description = $r['description'];
            $content->category = self::resolveCategoryValue($r);

            $separators = apply_filters('cegg_awin_category_separators', array('>', '|'), $r);
            $separators = is_array($separators) ? $separators : array();

            $path = ProductHelper::parseCategoryPath($content->category, $separators);

            // A single label parses to one segment equal to itself; only a
            // genuinely delimited value becomes a path.
            if ($path && $path !== array(trim($content->category)))
            {
                $content->categoryPath = $path;
                $content->category     = end($path);
            }
            $content->price = (float)$r['search_price'];
            $content->merchant = $r['merchant_name'];
            $content->currencyCode = $r['currency'];
            if (isset($r['brand_name']))
                $content->manufacturer = $r['brand_name'];

            if (isset($r['delivery_cost']))
                $content->shipping_cost = self::extractShippingCost($r['delivery_cost']);
            else
                $content->shipping_cost = null;

            $content->ean = $product['ean'];

            $pairs = self::getMerchantDomainPairs();
            if (isset($pairs[$content->merchant]))
            {
                $content->domain = $pairs[$content->merchant];
            }
            elseif (!strstr($content->orig_url, 'https://www.awin1.com'))
            {
                $content->domain = TextHelper::getHostName($content->orig_url);
            }

            if (!empty($r['average_rating']))
            {
                $content->rating = TextHelper::ratingPrepare($r['average_rating']);
                $content->ratingDecimal = TextHelper::ratingPrepare($r['average_rating']);
            }
            if (!empty($r['product_price_old']))
            {
                $content->priceOld = (float)$r['product_price_old'];
            }
            elseif (!empty($r['base_price_amount']))
            {
                $content->priceOld = (float)$r['base_price_amount'];
            }
            elseif (!empty($r['base_price']))
            {
                $content->priceOld = (float)$r['base_price'];
            }

            if ($content->priceOld == $content->price)
            {
                $content->priceOld = 0;
            }

            if (isset($r['in_stock']) && !(int) $r['in_stock'])
            {
                $content->stock_status = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
            }
            else
            {
                $content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
            }

            $content->extra = new ExtraDataAwin;
            ExtraDataAwin::fillAttributes($content->extra, $r);

            $data[] = $content;
        }

        return $data;
    }

    public function doRequestItems(array $items)
    {
        $this->maybeScheduleImport();
        foreach ($items as $key => $item)
        {
            $relinked = false;
            $product = $this->product_model->searchById($item['unique_id']);

            if (!$product)
            {
                $product = $this->findFeedProductByEan($item);
                $relinked = (bool) $product;
            }

            if (!$product)
            {
                if ($this->product_model->count())
                {
                    $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                }
                continue;
            }
            if (!$r = unserialize($product['product']))
            {
                continue;
            }

            $items[$key]['price'] = $r['search_price'];
            if (isset($r['in_stock']) && !(int) $r['in_stock'])
            {
                $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
            }
            else
            {
                $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_IN_STOCK;
            }

            $items[$key]['img'] = $r['merchant_image_url'];

            if ($relinked)
            {
                // unique_id is deliberately left as it is: it keys the saved data array
                // (ContentManager::setIds) and the price history rows, so rewriting it would
                // orphan both and drop the offer's price chart. Only the links carry the
                // aw_product_id, so only they have to follow the new one.
                $items[$key]['url'] = $r['aw_deep_link'];
                $items[$key]['orig_url'] = $r['merchant_deep_link'];

                // The unique_id keeps pointing at the retired id, so extra.aw_product_id is
                // the only record of which feed row the offer actually resolves to now.
                if (isset($items[$key]['extra']) && is_array($items[$key]['extra']))
                {
                    $items[$key]['extra']['aw_product_id'] = $r['aw_product_id'];
                    $items[$key]['extra']['merchant_product_id'] = isset($r['merchant_product_id']) ? $r['merchant_product_id'] : '';
                }
            }
        }

        return $items;
    }

    /**
     * Fallback lookup for an offer whose aw_product_id is no longer in the feed.
     *
     * AWIN issues a new aw_product_id when a merchant re-lists a product that had dropped out
     * of the feed, which otherwise leaves the mapped offer out of stock for good. The EAN is
     * the only stable identifier the saved offer already carries, so match on that - scoped to
     * the same merchant, and only when it resolves to exactly one candidate. Anything fuzzier
     * risks re-pointing the offer at a different product or a different shop.
     *
     * @return array|null Feed product row, or null when nothing can be matched safely.
     */
    private function findFeedProductByEan(array $item)
    {
        if (empty($item['ean']) || empty($item['merchant']))
        {
            return null;
        }

        $ean = TextHelper::fixEan($item['ean']);
        if (!TextHelper::isEan($ean))
        {
            return null;
        }

        if (!$candidates = $this->product_model->searchByEan($ean, self::EAN_RELINK_CANDIDATES))
        {
            return null;
        }

        $merchant = self::normalizeMerchantName($item['merchant']);
        $matched = array();
        foreach ($candidates as $candidate)
        {
            if (!$r = unserialize($candidate['product']))
            {
                continue;
            }
            if (empty($r['merchant_name']) || self::normalizeMerchantName($r['merchant_name']) !== $merchant)
            {
                continue;
            }

            $matched[] = $candidate;
        }

        if (count($matched) !== 1)
        {
            return null;
        }

        return $matched[0];
    }

    /**
     * Saved offers are passed through wp_strip_all_tags() on save while feed rows are not,
     * so put both merchant names on a common footing before comparing them.
     */
    private static function normalizeMerchantName($merchant)
    {
        return mb_strtolower(trim(\wp_strip_all_tags((string) $merchant)));
    }

    private function buildFeedUrl()
    {
        $url = $this->config('datafeed_url');

        if (!$path = parse_url($url, PHP_URL_PATH))
        {
            return '';
        }

        $res = 'https://productdata.awin.com/datafeed/download';

        $params = array('apikey', 'language', 'cid', 'fid', 'bid', 'adultcontent');
        foreach ($params as $param)
        {
            $value = TextHelper::getParamFromPath($path, $param);
            if ($param == 'apikey' && !$value)
            {
                return '';
            }
            if ($value)
            {
                $res .= '/' . $param . '/' . rawurlencode($value);
            }
        }

        $default_params = array(
            'columns' => 'aw_deep_link,product_name,aw_product_id,merchant_product_id,merchant_image_url,description,merchant_category,search_price,merchant_name,merchant_id,category_name,category_id,aw_image_url,currency,store_price,delivery_cost,merchant_deep_link,language,last_updated,reviews,average_rating,rating,number_available,in_stock,stock_quantity,valid_from,valid_to,is_for_sale,web_offer,pre_order,base_price_amount,product_price_old,base_price,ean,brand_name,product_GTIN',
            'format' => 'csv',
            'delimiter' => '%2C',
            'compression' => 'zip',
        );

        foreach ($default_params as $param => $value)
        {
            $res .= '/' . $param . '/' . $value;
        }

        return $res;
    }

    public function renderSearchPanel()
    {
        $this->render('search_panel', array('module_id' => $this->getId()));
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

    public static function getPriceParamMap()
    {
        return [
            'min' => 'price_min',
            'max' => 'price_max',
        ];
    }

    public function viewDataPrepare($data)
    {
        $template = (string) $this->config('subid');

        $template = preg_replace('/^\s*clickref\s*=\s*/i', '', $template ?? '');
        $template = trim(sanitize_text_field($template));

        if ($template === '')
        {
            return $data;
        }

        $param = apply_filters('cegg_awin_clickref_param', 'clickref');

        foreach ($data as $key => $item)
        {

            $url = $item['url'];

            $value = (string) LinkHandler::getUrlTemplate($url, $template, $item);

            $url = remove_query_arg($param, $url);
            $url = add_query_arg($param, $value, $url);

            $data[$key]['url'] = $url;
        }

        return parent::viewDataPrepare($data);
    }
}
