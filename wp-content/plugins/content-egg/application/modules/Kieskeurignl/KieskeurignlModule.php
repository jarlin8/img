<?php

namespace ContentEgg\application\modules\Kieskeurignl;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\libs\kieskeurignl\KieskeurignlApi;
use ContentEgg\application\modules\Kieskeurignl\ExtraDataKieskeurignl;
use ContentEgg\application\helpers\ArrayHelper;


/**
 * KieskeurignlModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2023 keywordrush.com
 */
class KieskeurignlModule extends AffiliateParserModule
{

    public function info()
    {
        return array(
            'name' => 'Kieskeurignl',
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/kieskeurignl',
        );
    }

    public function releaseVersion()
    {
        return '11.0.0';
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

    public function doRequest($keyword, $query_params = array(), $is_autoupdate = false)
    {
        if (!$product_id = $this->findProductId($keyword))
            return array();

        if (!$result = $this->getOffers($product_id))
            return array();

        if ($is_autoupdate)
            $limit = $this->config('entries_per_page_update');
        else
            $limit = $this->config('entries_per_page');

        return $this->prepareResults($result, $limit);
    }

    private function getOffers($product_id)
    {
        $client = $this->getApiClient();
        $result = $client->getOffers($product_id);

        if (!isset($result['section']) || !is_array($result['section']))
            return array();

        return $result;
    }

    private function prepareResults($result, $limit = 999)
    {
        $content = new ContentProduct;

        $s = $result['section'];

        $content->title = '';
        if (!empty($s['brand']))
            $content->title .= $s['brand'] . ' ';
        if (!empty($s['type']))
            $content->title .= $s['type'] . ' ';
        if (!empty($s['type_extra']))
            $content->title .= $s['type_extra'];
        $content->title = trim($content->title);

        $content->currencyCode = 'EUR';
        if (isset($s['image_templates']['image_template']))
        {
            if (is_array($s['image_templates']['image_template']))
                $img = reset($s['image_templates']['image_template']);
            else
                $img = $s['image_templates']['image_template'];

            $content->img = str_replace('/_.', '/l.', $img);
        }
        if (TextHelper::isEan($s['ean']))
            $content->ean = TextHelper::fixEan($s['ean']);

        if (!empty($s['brand']))
            $content->manufacturer = $s['brand'];

        $features = array();
        if (isset($s['specification']) && is_array($s['specification']) && isset($s['specification'][0]))
        {
            $content->features = array();
            foreach ($s['specification'] as $d)
            {
                if (strstr($d['value'], 'images.kieskeurig'))
                    continue;

                $d['label'] = ucfirst(str_replace('tile-', '', $d['label']));
                $feature = array(
                    'name' => \sanitize_text_field($d['label']),
                    'value' => \sanitize_text_field($d['value']),
                );
                $features[] = $feature;
            }
        }

        $content->extra = new ExtraDataKieskeurignl();
        ExtraDataKieskeurignl::fillAttributes($content->extra, $s);

        $offers = $result['searchresult']['prices']['price'];

        if (!isset($offers[0]) && isset($offers['id']))
            $offers = array($offers);

        $data = array();
        foreach ($offers as $i => $r)
        {
            $c = clone $content;
            $c->unique_id = $r['id'];
            $c->url = $r['deeplink'];
            $c->price = $r['pricevalue'];

            if (!empty($r['text_ad']))
                $c->description = $r['text_ad'];
            elseif (!empty($r['info']))
                $c->description = $r['info'];

            $c->merchant = $r['customer'];

            if ($domain = self::getMerchantDomain($r['customer']))
                $c->domain = $domain;
            else
            {
                $merchant = strtolower($r['customer']);
                if (TextHelper::isValidDomainName($merchant))
                    $c->domain = $merchant;
            }

            /*
            if ($r['stock'] == 'yes')
                $content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
            */
            if ($r['stock'] == 'no')
                $content->stock_status = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;

            if ($i == 0 && $features)
                $c->features = $features;

            ExtraDataKieskeurignl::fillAttributes($c->extra, $r);

            $data[] = $c;

            if (count($data) >= $limit)
                break;
        }

        return $data;
    }

    public function doRequestItems(array $items)
    {
        $product_ids = array_map(function ($element)
        {
            return $element['ean'];
        }, $items);

        $product_ids = array_unique($product_ids);
        $results = array();
        foreach ($product_ids as $product_id)
        {
            if (!$offers = $this->getOffers($product_id))
                continue;

            $results = array_merge($results, $this->prepareResults($offers));
        }

        $new = array();
        foreach ($results as $r)
        {
            $new[$r->unique_id] = $r;
        }

        // assign new data
        foreach ($items as $unique_id => $item)
        {
            if (!isset($new[$unique_id]))
            {
                $items[$unique_id]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                continue;
            }

            $result = $new[$unique_id];

            $fields = array(
                'price',
                'priceOld',
                'availability',
                'stock_status',
                'url',
            );
            foreach ($fields as $field)
            {
                $items[$unique_id][$field] = $result->$field;
            }

            $items[$unique_id]['extra'] = ArrayHelper::object2Array($result->extra);
        }

        return $items;
    }

    private function findProductId($keyword)
    {
        if ($pid = self::parsePidFromUrl($keyword))
            $keyword = $pid;

        if (self::isPid($keyword))
            return $keyword;

        if (TextHelper::isEan($keyword))
            return $keyword;

        $options = array();
        $options['ps'] = 1; // number of search results

        $client = $this->getApiClient();
        $results = $client->search($keyword, $options);

        if (!isset($results['searchresult']['items']['product']))
            return array();

        $results = $results['searchresult']['items']['product'];

        if (!isset($results[0]) && isset($results['@attributes']))
            $results = array($results);

        $result = reset($results);

        return $result['@attributes']['sid'];
    }

    private function getApiClient()
    {
        return new KieskeurignlApi($this->config('token'), $this->config('affiliate_id'), $this->config('country'));
    }

    public function renderResults()
    {
        PluginAdmin::render('_metabox_results', array('module_id' => $this->getId()));
    }

    public function renderSearchResults()
    {
        PluginAdmin::render('_metabox_search_results', array('module_id' => $this->getId()));
    }

    public static function parsePidFromUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL))
            return false;

        $url = strtok($url, '?');
        if (preg_match('~product\/(\d+)-~', $url, $matches))
            return $matches[1];
        else
            return false;
    }

    static function isPid($pid)
    {
        if (preg_match('~\d{6,12}~', $pid))
            return true;
        else
            return false;
    }

    public static function getMerchantDomain($merchant)
    {
        $list = self::getMerchantDomains();
        if (isset($list[$merchant]))
            return $list[$merchant];
        else
            return false;
    }

    public static function getMerchantDomains()
    {
        $m2d = array(
            'Amazon' => 'amazon.nl',
            'BCC' => 'bcc.nl',
            'Bol.com' => 'bol.com',
            'Blokker connect' => 'blokker.nl',
            'Bol.com Plaza' => 'bol.com',
            'Midimedia' => 'midimedia.nl',
            'Bemmel en Kroon' => 'bemmelenkroon.nl',
            'Keukenloods' => 'keukenloods.nl',
            'Beterwitgoed' => 'beterwitgoed.nl',
            'HelloTV' => 'hellotv.nl',
            'Expert' => 'expert.nl',
            'Art & Craft' => 'artencraft.nl',
            'Philips DA' => 'philips.nl',
            'Amazon Marketplace' => 'amazon.nl',
            'Tummers' => 'eptummers.nl',
        );

        return \apply_filters('cegg_kieskeurignl_merchant2domain', $m2d);
    }
}
