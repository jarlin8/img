<?php

namespace Keywordrush\AffiliateEgg;

defined('\ABSPATH') || exit;

/**
 * ShopeevnParser class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2022 keywordrush.com
 */
class ShopeevnParser extends ShopParser {

    protected $charset = 'utf-8';
    protected $currency = 'VND';
    private $_product;
    protected $canonical_domain = 'https://shopee.vn';
    protected $user_agent = array('ia_archiver');

    public function parseCatalog($max)
    {
        $url = $this->canonical_domain . '/api/v4/search/search_items?by=relevancy&limit=50&newest=0&order=desc&page_type=search&version=2';

        $keyword = TextHelper::getQueryVar('keyword', $this->getUrl());

        if (preg_match('/-cat\.([\d\.]+)/', $this->getUrl(), $matches))
        {
            $parts = explode('.', $matches[1]);
            $category_id = $parts[count($parts) - 1];
        } elseif (preg_match('/-col\.(\d+)/', $this->getUrl(), $matches))
        {
            $category_id = $matches[1];
            $url = \add_query_arg('page_type', 'collection', $url);
        } else
            $category_id = '';

        if (!$keyword && !$category_id)
            return array();

        if ($keyword)
            $url = \add_query_arg('keyword', $keyword, $url);

        if ($category_id)
            $url = \add_query_arg('match_id', $category_id, $url);


        if ($page = TextHelper::getQueryVar('page', $this->getUrl()))
        {
            if ($page > 1)
                $url = \add_query_arg('newest', $page * 50, $url);
        }
        
        $result = $this->getRemoteJson($url);
        if (!$result || !isset($result['items']))
            return false;
        $urls = array();

        foreach ($result['items'] as $item)
        {
            $urls[] = $this->canonical_domain . '/' . str_replace(' ', '-', $item['item_basic']['name']) . '-i.' . $item['shopid'] . '.' . $item['itemid'];
        }
        return $urls;
    }

    public function parseTitle()
    {
        $this->_getProduct();
        if (!$this->_product)
            return;
        if (isset($this->_product['name']))
            return $this->_product['name']; 
    }

    public function _getProduct()
    {
        $item_id = 0;
        $shop_id = 0;

        if (preg_match('~\-i\.(\d+\.\d+)~', $this->getUrl(), $matches))
        {
            $ids = $matches[1];
            $ids = explode('.', $ids);
            $item_id = $ids[1];
            $shop_id = $ids[0];
        }

        if (preg_match('~\/product\/(\d+)\/(\d+)~', $this->getUrl(), $matches))
        {
            $item_id = $matches[2];
            $shop_id = $matches[1];
        }

        if (!$item_id || !$shop_id)
            return; 

        $result = $this->getRemoteJson($this->canonical_domain . '/api/v4/item/get?itemid=' . urlencode($item_id) . '&shopid=' . urlencode($shop_id));
        
        if (!$result || !isset($result['data']))
            return;

        $this->_product = $result['data'];
        return $this->_product;
    }

    public function parseDescription()
    {
        if (isset($this->_product['description']))
            return $this->_product['description'];
    }

    public function parsePrice()
    {
        if (isset($this->_product['price']))
            return $this->_product['price'] / 100000;
        if (isset($this->_product['price_min']))
            return $this->_product['price_min'] / 100000;
    }

    public function parseOldPrice()
    {
        if (isset($this->_product['price_before_discount']))
            return $this->_product['price_before_discount'] / 100000;
        if (isset($this->_product['price_min_before_discount']))
            return $this->_product['price_min_before_discount'] / 100000;
    }

    public function parseManufacturer()
    {
        if (isset($this->_product['brand']))
            return $this->_product['brand'];
    }

    public function parseImg()
    {
        if (isset($this->_product['image']))
            return str_replace('https://', 'https://cf.', $this->canonical_domain) . '/file/' . $this->_product['image'];
    }

    public function parseExtra()
    {
        $extra = array();
        if (isset($this->_product['rating_star']))
            $extra['rating'] = TextHelper::ratingPrepare($this->_product['rating_star']);
        return $extra;
    }

    public function isInStock()
    {
        if (isset($this->_product['status']) && !$this->_product['status'])
            return false;

        if (isset($this->_product['stock']) && !$this->_product['stock'])
            return false;

        return true;
    }

}
