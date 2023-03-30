<?php

namespace ContentEgg\application\modules\Shopee;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\libs\shopee\ShopeeApi;
use ContentEgg\application\modules\Shopee\ExtraDataShopee;
use ContentEgg\application\libs\shopee\ShopeeLocales;
use ContentEgg\application\components\LinkHandler;


/**
 * ShopeeModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2023 keywordrush.com
 */
class ShopeeModule extends AffiliateParserModule
{

    public function info()
    {
        return array(
            'name' => 'Shopee (beta)',
            'description' => sprintf(__('Adds products from %s.', 'content-egg'), 'Shopee'),
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/shopee',
        );
    }

    public function releaseVersion()
    {
        return '11.2.0';
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

    public function isUrlSearchAllowed()
    {
        return false;
    }

    public function doRequest($keyword, $query_params = array(), $is_autoupdate = false)
    {
        if ($is_autoupdate)
            $limit = $this->config('entries_per_page_update');
        else
            $limit = $this->config('entries_per_page');

        $keyword = str_replace('"', '', $keyword);
        $keyword = str_replace('\'', '', $keyword);
        $keyword = str_replace('\\', '', $keyword);
        $offer = sprintf('keyword: \"%s\"', $keyword);
        $offer .= sprintf(' limit: %d', $limit);
        $offer .= sprintf(' sortType: %d', (int) $this->config('sort_type'));

        $fields = array(
            'itemId',
            'commissionRate',
            'appExistRate',
            'appNewRate',
            'webExistRate',
            'webNewRate',
            'commission',
            'price',
            'sales',
            'imageUrl',
            'productName',
            'shopName',
            'productLink',
            'offerLink',
            'periodStartTime',
            'periodEndTime',
        );

        $node = join(', ', $fields);

        $query = 'query {productOfferV2(' . $offer . ') {nodes {' . $node . '}}}';
        $payload = '{"query":"' . $query . '"}';

        $client = $this->getApiClient();
        $results = $client->search($payload);

        if (!$results || !isset($results['data']['productOfferV2']['nodes']) || !is_array($results['data']['productOfferV2']['nodes']))
            return array();

        $locale = $this->config('locale');
        return $this->prepareResults($results['data']['productOfferV2']['nodes'], $locale);
    }

    private function prepareResults($results, $locale)
    {
        $data = array();
        foreach ($results as $r)
        {
            $data[] = $this->prepareResult($r, $locale);
        }

        return $data;
    }

    private function prepareResult($r, $locale)
    {
        $content = new ContentProduct;

        $content->unique_id = $r['itemId'];
        $content->title = $r['productName'];
        $content->orig_url = $r['productLink'];
        $content->url = $this->generateAffiliateUrl($content->orig_url);
        $content->price = (float) $r['price'];
        $content->img = $r['imageUrl'];
        $content->currencyCode = ShopeeLocales::getCurrencyCode($locale);
        $content->domain = TextHelper::getHostName($content->orig_url);
        $content->merchant = 'Shopee';

        $content->extra = new ExtraDataShopee();
        $content->extra->locale = $locale;
        ExtraDataShopee::fillAttributes($content->extra, $r);
        return $content;
    }

    private function generateAffiliateUrl($url)
    {
        if ($deeplink = $this->config('deeplink'))
            return LinkHandler::createAffUrl($url, $deeplink);

        if ($affiliate_id = $this->config('affiliate_id'))
            return 'https://shope.ee/an_redir?origin_link=' . urlencode($url) . '&affiliate_id=' . urlencode($affiliate_id);

        return $url;
    }

    public function viewDataPrepare($data)
    {
        foreach ($data as $key => $d)
        {
            $data[$key]['url'] = $this->generateAffiliateUrl($d['orig_url']);
        }

        return parent::viewDataPrepare($data);
    }

    private function getApiClient()
    {
        return new ShopeeApi($this->config('app_id'), $this->config('api_key'), $this->config('locale'));
    }

    public function renderResults()
    {
        PluginAdmin::render('_metabox_results', array('module_id' => $this->getId()));
    }

    public function renderSearchResults()
    {
        PluginAdmin::render('_metabox_search_results', array('module_id' => $this->getId()));
    }
}
