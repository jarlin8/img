<?php

namespace ContentEgg\application\modules\AmazonNoApi;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\libs\amazon\AmazonLocales;
use ContentEgg\application\modules\Amazon\AmazonModule;
use ContentEgg\application\modules\AmazonNoApi\ExtraDataAmazonNoApi;
use ContentEgg\application\helpers\ArrayHelper;
use ContentEgg\application\libs\amazon\AmazonClient;

use function ContentEgg\prn;
use function ContentEgg\prnx;

/**
 * AmazonNoApiModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2023 keywordrush.com
 */
class AmazonNoApiModule extends AffiliateParserModule
{
    private $api_client = null;

    public function info()
    {
        return array(
            'name' => 'Amazon No API',
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/amazon-no-api-module',
        );
    }

    public function isDeprecated()
    {
        return false;
    }

    public function releaseVersion()
    {
        return '12.5.0';
    }

    public function getParserType()
    {
        return self::PARSER_TYPE_PRODUCT;
    }

    public function defaultTemplateName()
    {
        return 'data_item';
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
        @set_time_limit(300);

        if (!empty($query_params['locale']) && AmazonLocales::getLocale($query_params['locale']))
            $locale = $query_params['locale'];
        else
            $locale = $this->config('locale');

        if ($is_autoupdate)
            $limit = $this->config('entries_per_page_update');
        else
            $limit = $this->config('entries_per_page');

        $keyword = trim($keyword);
        if (TextHelper::isAsin($keyword))
        {
            $host = AmazonLocales::getDomain($locale);
            $keyword = 'https://www.' . $host . '/dp/' . $keyword . '/';
        }

        $client = $this->getApiClient($locale);
        $results = array();

        try
        {

            if (filter_var($keyword, FILTER_VALIDATE_URL) && AmazonModule::parseAsinFromUrl($keyword))
            {
                if ($result = $client->product($keyword))
                    $results = array($result);
            }
            else
                $results = $client->search($keyword, $limit);
        }
        catch (\Exception $e)
        {

            if ($e->getCode() == 503)
            {
                $m = sprintf('It seems your server IP has been blocked by Amazon. To resolve this, please activate the <a target="_blank" href="%s">built-in scraping services</a> in the module settings.', 'https://ce-docs.keywordrush.com/modules/affiliate/amazon-no-api-module');
                throw new \Exception($m, $e->getCode());
            }
            else
                throw new \Exception($e->getMessage(), $e->getCode());
        }

        if (!$results)
            return array();

        return $this->prepareResults($results, $locale);
    }

    public function doRequestItems(array $items)
    {
        @set_time_limit(300);

        $i = 0;
        $product_sleep = 500000;

        foreach ($items as $key => $item)
        {
            if ($product_sleep && $i > 0)
                usleep($product_sleep);

            $i++;

            $client = $this->getApiClient($item['extra']['locale']);
            $client->setLocale($item['extra']['locale']);

            $url = \add_query_arg('tag', false, $item['orig_url']);

            try
            {
                $result = $client->product($url);
            }
            catch (\Exception $e)
            {
                if ($e->getCode() == 404 || $e->getCode() == 410)
                    $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;

                continue;
            }

            if (!$result)
            {
                //$items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                continue;
            }

            $product = $this->prepareResult($result, $item['extra']['locale']);

            $items[$key]['price'] = $product->price;
            $items[$key]['priceOld'] = $product->priceOld;
            $items[$key]['currencyCode'] = $product->currencyCode;
            $items[$key]['currency'] = TextHelper::currencyTyping($product->currencyCode);
            $items[$key]['stock_status'] = $product->stock_status;
            $items[$key]['rating'] = $product->rating;
            $items[$key]['ratingDecimal'] = $product->ratingDecimal;
            $items[$key]['url'] = $product->url;
            $items[$key]['img'] = $product->img;
            $items[$key]['extra'] = ArrayHelper::object2Array($product->extra);
        }

        return $items;
    }

    private function prepareResults($results, $locale)
    {
        $data = array();
        foreach ($results as $r)
        {
            if ($d = $this->prepareResult($r, $locale))
                $data[] = $d;
        }

        return $data;
    }

    private function prepareResult($r, $locale)
    {
        $content = new ContentProduct;
        $extra = new ExtraDataAmazonNoApi;
        $asin = AmazonClient::parseAsinFromUrl($r['url']);
        if (!$asin)
            return false;

        $associate_tag = $this->getAssociateTagForLocale($locale);

        $content->unique_id = $locale . '-' . $asin;
        $content->orig_url = $r['url'];
        $content->domain = AmazonNoApiConfig::getDomainByLocale($locale);
        $content->merchant = ucfirst($content->domain);
        if (!empty($r['orig_img_large']))
            $content->img_large = $r['orig_img_large'];

        foreach ($r as $field => $value)
        {
            if (property_exists($content, $field))
                $content->$field = $value;
        }

        $content->currency = TextHelper::currencyTyping($content->currencyCode);

        if (isset($r['extra']['images']))
        {
            $content->images = $r['extra']['images'];
            unset($r['extra']['images']);
        }

        if (isset($r['extra']['category']))
            $content->category = $r['extra']['category'];

        if (isset($r['extra']['categoryPath']))
            $content->categoryPath = $r['extra']['categoryPath'];

        $extra->ASIN = $asin;
        $extra->locale = $locale;
        $extra->associate_tag = $associate_tag;
        $extra->addToCartUrl = $this->generateAddToCartUrl($locale, $extra->ASIN);

        if ($this->config('link_type') == 'add_to_cart')
            $content->url = $extra->addToCartUrl;
        else
            $content->url = \add_query_arg('tag', $associate_tag, $content->orig_url);

        ExtraDataAmazonNoApi::fillAttributes($extra, $r);

        $content->extra = $extra;

        if (isset($r['extra']['comments']))
        {
            $content->extra->comments = $r['extra']['comments'];
            unset($r['extra']['comments']);
        }

        $content->extra->data = $r['extra'];

        return $content;
    }

    private function getApiClient($locale)
    {
        if ($this->api_client === null)
        {
            $this->api_client = new AmazonClient($locale);

            $services = array('scraperapi', 'proxycrawl', 'scrapingdog');
            foreach ($services as $service)
            {
                if ($token_value = $this->config($service . '_token'))
                {
                    $method = 'set' . ucfirst($service) . 'Token';
                    $this->api_client->$method($token_value);
                    break;
                }
            }
        }

        return $this->api_client;
    }

    public function getAssociateTagForLocale($locale)
    {
        if ($locale == $this->config('locale'))
        {
            return $this->config('associate_tag');
        }
        else
        {
            return $this->config('associate_tag_' . $locale);
        }
    }

    /**
     * Add to shopping cart url
     * @link: https://webservices.amazon.com/paapi5/documentation/add-to-cart-form.html
     * @link: https://affiliate-program.amazon.com/help/node/topic/G9SMD8TQHFJ7728F
     */
    private function getAmazonAddToCartUrl($locale)
    {
        return 'https://www.' . AmazonNoApiConfig::getDomainByLocale($locale) . '/gp/aws/cart/add.html';
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

    private function generateAddToCartUrl($locale, $asin)
    {
        return $this->getAmazonAddToCartUrl($locale) .
            '?ASIN.1=' . $asin . '&Quantity.1=1' .
            '&AssociateTag=' . $this->getAssociateTagForLocale($locale);
    }

    public function viewDataPrepare($data)
    {
        foreach ($data as $key => $d)
        {
            $tag_id = $this->getAssociateTagForLocale($d['extra']['locale']);

            if (strstr($data[$key]['url'], 'AssociateTag='))
                $data[$key]['url'] = TextHelper::addUrlParam($data[$key]['url'], 'AssociateTag', $tag_id);
            else
                $data[$key]['url'] = TextHelper::addUrlParam($data[$key]['url'], 'tag', $tag_id);

            if ($this->config('hide_prices') == 'hide')
            {
                $data[$key]['price'] = 0;
                $data[$key]['priceOld'] = 0;
                $data[$key]['stock_status'] = 0;
            }
        }

        return parent::viewDataPrepare($data);
    }
}
