<?php

namespace ContentEgg\application\modules\Bolcom;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\libs\bolcom\BolcomJwtApi;
use ContentEgg\application\components\LinkHandler;
use ContentEgg\application\Plugin;

use function ContentEgg\prn;
use function ContentEgg\prnx;

/**
 * BolcomModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2024 keywordrush.com
 */
class BolcomModule extends AffiliateParserModule
{

    public function info()
    {
        if (\is_admin() && !Plugin::isFree())
        {
            \add_action('admin_notices', array(__CLASS__, 'updateNotice'));
        }
        return array(
            'name' => 'Bolcom',
            'description' => sprintf(__('Adds products from %s.', 'content-egg'), 'Bol.com'),
        );
    }

    public static function updateNotice()
    {
        if (!BolcomConfig::getInstance()->option('is_active'))
            return;

        if (BolcomConfig::getInstance()->option('client_id') && BolcomConfig::getInstance()->option('client_secret'))
            return;

        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p>' . sprintf(__('Let op! Binnenkort vervallen de huidige API keys. <a href="%s">Klik hier</a> om de nieuwe API toegang te activeren.', 'content-egg'), \get_admin_url(\get_current_blog_id(), 'admin.php?page=content-egg-modules--Bolcom')) . '</p>';
        echo '</div>';
    }

    public function releaseVersion()
    {
        return '4.1.0';
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
        $client = $this->getApiClient();

        $options = array();

        if ($is_autoupdate)
            $limit = $this->config('entries_per_page_update');
        else
            $limit = $this->config('entries_per_page');

        if ($limit > 50)
            $limit = 50;

        $options['page-size'] = $limit;
        $options['country-code'] = $this->config('country');

        $sort = $this->config('sort');
        if (in_array($sort, array('RELEVANCE', 'POPULARITY', 'PRICE_ASC', 'PRICE_DESC', 'RELEASE_DATE', 'RATING')))
            $options['sort'] = $sort;

        if ($this->config('ids'))
            $options['category-id'] = (int) $this->config('ids');

        $options['include-categories'] = 'true';
        $options['include-image'] = 'true';
        $options['include-offer'] = 'true';
        $options['include-rating'] = 'true';

        $results = $client->search($keyword, $options);

        if (!isset($results['results']) || !is_array($results['results']))
            return array();

        $products = $this->prepareResults($results['results']);

        foreach ($products as $i => $product)
        {
            $this->applaySpecsAndImage($product);
        }

        return $products;
    }

    private function applaySpecsAndImage(ContentProduct $product)
    {
        if (!$product->ean)
            return $product;

        $options = array();
        $options['country-code'] = $this->config('country');
        $options['include-specifications'] = 'true';
        $options['include-image'] = 'true';

        try
        {
            $r = $this->getApiClient()->product($product->ean, $options);
        }
        catch (\Exception $e)
        {
            return $product;
        }

        // large image
        if (isset($r['image']['url']))
            $product->img = $r['image']['url'];

        $features = array();
        if (isset($r['specificationGroups']) && is_array($r['specificationGroups']))
        {
            foreach ($r['specificationGroups'] as $spec_group)
            {

                foreach ($spec_group['specifications'] as $spec)
                {
                    $value = \strip_tags(join(', ', $spec['values']));

                    if (strstr($value, 'Deze informatie volgt nog'))
                        continue;

                    $feature = array(
                        'group' => \sanitize_text_field($spec_group['title']),
                        'name' => \sanitize_text_field($spec['name']),
                        'value' => $value,
                    );
                    $features[] = $feature;
                }
            }
        }

        $product->features = $features;

        return $product;
    }

    private function prepareResults($results)
    {
        $data = array();

        foreach ($results as $key => $r)
        {
            $content = new ContentProduct;

            $content->unique_id = $r['bolProductId'];
            $content->title = $r['title'];
            $content->orig_url = $r['url'];
            $content->domain = 'bol.com';
            $content->currencyCode = 'EUR';
            $content->url = $this->createAffUrl($content->orig_url, (array) $content);

            if (!empty($r['ean']))
                $content->ean = $r['ean'];

            $content->description = $r['description'];
            if ($max_size = $this->config('description_size'))
                $content->description = TextHelper::truncateHtml($content->description, $max_size);

            $content->img = $r['image']['url'];
            if (!empty($r['offer']['price']))
            {
                $content->price = $r['offer']['price'];
                $content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
            }
            else
                $content->stock_status = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;

            if (isset($r['offer']['strikethroughPrice']))
                $content->priceOld = $r['offer']['strikethroughPrice'];

            if (!empty($r['rating']))
            {
                $content->rating = TextHelper::ratingPrepare($r['rating']);
                $content->ratingDecimal = (float) $r['rating'];
            }

            $content->extra = new ExtraDataBolcom();
            if (!empty($r['offer']['deliveryDescription']))
                $content->extra->deliveryDescription = $r['offer']['deliveryDescription'];
            ExtraDataBolcom::fillAttributes($content->extra, $r);

            $data[] = $content;
        }

        return $data;
    }

    public function doRequestItems(array $items)
    {
        $client = $this->getApiClient();

        $options = array();
        $options['country-code'] = $this->config('country');

        foreach ($items as $i => $item)
        {
            if (empty($item['ean']))
                continue;

            $offer = array();

            try
            {
                $offer = $client->offer($item['ean'], $options);
            }
            catch (\Exception $e)
            {
                if ($e->getCode() == 404)
                {
                    $items[$i]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                    continue;
                }

                continue;
            }

            if (!$offer)
                continue;

            // assign new data
            if (!empty($offer['price']))
            {
                $items[$i]['price'] = $offer['price'];
                $items[$i]['stock_status'] = ContentProduct::STOCK_STATUS_IN_STOCK;
            }
            else
            {
                $items[$i]['price'] = '';
                $items[$i]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
            }

            if (!empty($offer['strikethroughPrice']))
                $items[$i]['priceOld'] = $offer['strikethroughPrice'];
            else
                $items[$i]['priceOld'] = 0;

            $items[$i]['orig_url'] = $offer['url'];
            $items[$i]['url'] = $this->createAffUrl($items[$i]['url'], $items[$i]);

            $extra_fields = array('deliveryDescription', 'condition', 'isPreOrder', 'ultimateOrderTime', 'minDeliveryDate', 'maxDeliveryDate', 'releaseDate');
            foreach ($extra_fields as $field)
            {
                if (!empty($offer[$field]))
                    $items[$i][$field] = $offer[$field];
            }
        }

        return $items;
    }

    private function getApiClient()
    {
        $api_client = new BolcomJwtApi($this->config('client_id'), $this->config('client_secret'));
        $api_client->setLang($this->config('language'));
        $api_client->setAccessToken($this->getAccessToken());
        return $api_client;
    }

    public function viewDataPrepare($data)
    {
        foreach ($data as $key => $d)
        {
            $data[$key]['url'] = $this->createAffUrl($d['orig_url'], $d);
        }

        return parent::viewDataPrepare($data);
    }

    private function createAffUrl($url, $item)
    {
        // @link: https://affiliate.bol.com/nl/handleiding/handleiding-productfeed#:~:text=De%C2%A0productfeed%20koppelen,dat%20alles%20werkt

        $deeplink = 'https://partner.bol.com/click/click?p=1&t=url&s=' . urlencode($this->config('SiteId')) . '&url={{url_encoded}}&f=TXL';

        // replacement patterns can be applied
        if ($this->config('subId'))
            $deeplink .= '&subid=' . $this->config('subId');

        $deeplink .= '&name=' . urlencode(\apply_filters('cegg_bolcom_link_name_param', 'cegg'));

        return LinkHandler::createAffUrl($url, $deeplink, $item);
    }

    public function renderResults()
    {
        PluginAdmin::render('_metabox_results', array('module_id' => $this->getId()));
    }

    public function renderSearchResults()
    {
        PluginAdmin::render('_metabox_search_results', array('module_id' => $this->getId()));
    }

    public function requestAccessToken()
    {
        $api_client = new BolcomJwtApi($this->config('client_id'), $this->config('client_secret'));

        $response = $api_client->requestAccessToken();

        if (empty($response['access_token']) || empty($response['expires_in']))
        {
            throw new \Exception('Bolcom JWT API: Invalid Response Format.');
        }

        return array($response['access_token'], (int) $response['expires_in']);
    }
}
