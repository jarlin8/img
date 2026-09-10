<?php

namespace ContentEgg\application\modules\Impactradius;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\components\LinkHandler;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\libs\impactradius\ImpactradiusApi;
use ContentEgg\application\modules\Impactradius\ExtraDataImpactradius;

use function ContentEgg\prnx;;

/**
 * ImpactradiusModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ImpactradiusModule extends AffiliateParserModule
{
    public function isGtinSearchAllowed()
    {
        return true;
    }

    private $api_client = null;

    public function info()
    {
        return array(
            'name' => 'Impact Radius',
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/impactradius',
        );
    }

    public function releaseVersion()
    {
        return '3.0.0';
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

    public function doRequest($keyword, $query_params = array(), $is_autoupdate = false)
    {
        $options = array();

        if ($is_autoupdate)
        {
            $limit = $this->config('entries_per_page_update');
        }
        else
        {
            $limit = $this->config('entries_per_page');
        }

        $query = '';

        // price range filter
        $minprice = (float) $this->config('minprice');
        $maxprice = (float) $this->config('maxprice');
        if (!empty($query_params['minprice']))
        {
            $minprice = (float) $query_params['minprice'];
        }
        if (!empty($query_params['maxprice']))
        {
            $maxprice = (float) $query_params['maxprice'];
        }

        if ($minprice)
        {
            $query = 'CurrentPrice>=' . number_format($minprice, 2, '.', '');
        }

        if ($maxprice)
        {
            if ($query)
            {
                $query .= ' AND ';
            }
            $query .= 'CurrentPrice<=' . number_format($maxprice, 2, '.', '');
        }

        if ($this->config('in_stock'))
        {
            if ($query)
            {
                $query .= ' AND ';
            }
            $query .= 'StockAvailability="InStock"';
        }

        // CatalogId filter
        $catalogIdValue = (string) $this->config('CatalogId');
        if (!empty($query_params['CatalogId']))
        {
            $catalogIdValue = (string) $query_params['CatalogId'];
        }
        $catalogIds = array_filter(array_map('intval', TextHelper::getArrayFromCommaList($catalogIdValue)));
        if (!empty($catalogIds))
        {
            if ($query)
            {
                $query .= ' AND ';
            }
            $query .= 'CatalogId IN(' . implode(',', $catalogIds) . ')';
        }

        // CampaignId filter
        $campaignIdValue = (string) $this->config('CampaignId');
        if (!empty($query_params['CampaignId']))
        {
            $campaignIdValue = (string) $query_params['CampaignId'];
        }
        $campaignIds = array_filter(array_map('intval', TextHelper::getArrayFromCommaList($campaignIdValue)));
        if (!empty($campaignIds))
        {
            if ($query)
            {
                $query .= ' AND ';
            }
            $query .= 'CampaignId IN(' . implode(',', $campaignIds) . ')';
        }

        $options['Query'] = $query;

        $keywords = array($keyword);

        // Normalize to digits only (keeps the original too, for exact-match search)
        $digits = preg_replace('/\D+/', '', $keyword);

        // Treat 11-digit numeric strings as EAN-like as well
        if (TextHelper::isEan($digits) || preg_match('/^\d{11}$/', $digits))
        {
            $variants   = [$digits];

            // Common permutations (some feeds add/remove leading zeros)
            $variants[] = preg_replace('/^0/', '', $digits, 1); // drop one leading 0
            $variants[] = ltrim($digits, '0');                  // drop all leading 0s

            // Standardized lengths the plugin already tries
            $variants[] = TextHelper::fixEan($digits, 13);
            $variants[] = TextHelper::fixEan($digits, 12);

            // If merchant stored UPC without the leading zero (11 digits), also try with it
            if (strlen($digits) === 11)
            {
                $upc = '0' . $digits;                      // UPC-A (12)
                $variants[] = $upc;
                $variants[] = TextHelper::fixEan($upc, 13); // EAN-13 equivalent
            }

            // Some merchants drop the check digit from a 12-digit UPC → try first 11
            if (strlen($digits) === 12)
            {
                $variants[] = substr($digits, 0, 11);
            }

            $keywords = array_values(array_unique(array_merge($keywords, $variants)));
        }

        $merged = [];
        $seenIds = [];

        foreach ($keywords as $k)
        {
            $results = $this->getApiClient()->search($k, $options);

            if (!empty($results['Items']))
            {
                foreach ($results['Items'] as $item)
                {
                    if (!empty($item['Id']) && isset($seenIds[$item['Id']]))
                    {
                        continue;
                    }

                    $merged[] = $item;

                    if (!empty($item['Id']))
                    {
                        $seenIds[$item['Id']] = true;
                    }

                    if (count($merged) >= $limit)
                    {
                        break 2;
                    }
                }
            }
        }

        return $this->prepareResults($merged);
    }

    private function prepareResults($results)
    {
        $data = array();
        foreach ($results as $key => $r)
        {
            $content = new ContentProduct;
            $content->unique_id = $r['Id'];
            $content->sku = $r['CatalogItemId'];
            $content->title = $r['Name'];

            $content->description = strip_tags($r['Description']);
            if ($max_size = $this->config('description_size'))
            {
                $content->description = TextHelper::truncate($content->description, $max_size);
            }

            $content->manufacturer = $r['Manufacturer'];
            $content->url = $this->applySubIds($r['Url'], (array) $content);
            $content->domain = TextHelper::parseDomain($content->url, 'u');
            $content->img = $r['ImageUrl'];

            if (!empty($r['AdditionalImageUrls']) && is_array($r['AdditionalImageUrls']))
            {
                $content->images = $r['AdditionalImageUrls'];
            }

            $content->price = (float) $r['CurrentPrice'];

            if (!$content->price && $r['OriginalPrice'])
                $content->price = (float) $r['OriginalPrice'];
            elseif ((float) $r['OriginalPrice'] > $content->price)
                $content->priceOld = (float) $r['OriginalPrice'];

            $content->currencyCode = $r['Currency'];
            $content->availability = $r['StockAvailability'];

            if (in_array(strtolower($r['StockAvailability']), array('instock', 'preorder', 'backordered', 'backorder')))
            {
                $content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
            }
            elseif ($r['StockAvailability'])
            {
                $content->stock_status = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
            }
            else
            {
                $content->stock_status = ContentProduct::STOCK_STATUS_UNKNOWN;
            }

            $content->merchant = $r['CampaignName'];
            $content->category = $r['OriginalFormatCategory'];

            switch ($r['GtinType'])
            {
                case 'EAN':
                    $content->ean = $r['Gtin'];
                    break;
                case 'UPC':
                    $content->upc = $r['Gtin'];
                    break;
                case 'ISBN':
                    $content->isbn = $r['Gtin'];
                    break;
            }

            if (!$content->ean && $r['Gtin'])
                $content->ean = TextHelper::fixEan($r['Gtin']);

            $content->extra = new ExtraDataImpactradius;
            ExtraDataImpactradius::fillAttributes($content->extra, $r);
            $data[] = $content;
        }

        return $data;
    }

    public function doRequestItems(array $items)
    {
        foreach ($items as $key => $item)
        {
            if (empty($item['extra']['CatalogId']))
            {
                continue;
            }

            $result = $this->getApiClient()->product($item['extra']['CatalogId'], $item['unique_id']);

            if (!is_array($result) || !isset($result['Id']))
            {
                throw new \Exception('doRequestItems request error.');
            }

            // assign new price
            $items[$key]['url'] = $this->applySubIds($result['Url'], $items[$key]);
            $items[$key]['price'] = (float) $result['CurrentPrice'];

            if (!$items[$key]['price'])
                $items[$key]['price'] = (float) $result['OriginalPrice'];
            else
                $items[$key]['priceOld'] = (float) $result['OriginalPrice'];

            if ($result['StockAvailability'] && in_array(strtolower($result['StockAvailability']), array('instock', 'preorder', 'backordered', 'backorder')))
            {
                $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_IN_STOCK;
            }
            elseif ($result['StockAvailability'])
            {
                $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
            }
            else
            {
                $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_UNKNOWN;
            }

            if (!empty($result['ImageUrl']))
            {
                $items[$key]['img'] = $result['ImageUrl'];
            }
        }

        return $items;
    }

    public function viewDataPrepare($data)
    {
        if ($this->hasSubIds())
        {
            foreach ($data as $key => $d)
            {
                $data[$key]['url'] = $this->applySubIds($d['url'], $d);
            }
        }

        return parent::viewDataPrepare($data);
    }

    private function hasSubIds()
    {
        return (bool) ($this->config('subId1') || $this->config('subId2') || $this->config('subId3'));
    }

    /**
     * Append Impact SubId1..SubId3 parameters to an affiliate URL.
     *
     * Each configured value may contain {{placeholders}} which are resolved per link
     * and sanitized to an Impact-safe token (max 255 characters).
     */
    private function applySubIds($url, array $item)
    {
        foreach (array('subId1', 'subId2', 'subId3') as $param)
        {
            if (!$value = $this->config($param))
            {
                continue;
            }

            $value = LinkHandler::resolveSubId($value, $item, 255);
            if ($value === '')
            {
                continue;
            }

            $url = LinkHandler::getUrlWithTail($url, $param . '=' . $value);
        }

        return $url;
    }

    private function getApiClient()
    {
        if ($this->api_client === null)
        {
            $this->api_client = new ImpactradiusApi($this->config('AccountSid'), $this->config('AuthToken'));
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
}
