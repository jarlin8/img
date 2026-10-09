<?php

namespace ContentEgg\application\modules\Amazon;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\libs\amazon\AmazonApi;
use ContentEgg\application\libs\amazon\AmazonCreatorsApi;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\helpers\ArrayHelper;
use ContentEgg\application\helpers\TemplateHelper;
use ContentEgg\application\libs\amazon\AmazonLocales;
use ContentEgg\application\Plugin;

use function ContentEgg\prn;
use function ContentEgg\prnx;

/**
 * AmazonModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class AmazonModule extends AffiliateParserModule
{
    public function isGtinSearchAllowed()
    {
        return true;
    }

    const NOTICE_KEY = 'amazon_creators';

    /** @var AmazonApi|null */
    private $api_client_paapi5 = null;

    /** @var AmazonCreatorsApi[] Creators API clients, one per region group (NA/EU/FE). */
    private $api_clients_creators = array();

    public function info()
    {
        if (\is_admin())
        {
            \add_action('admin_notices', array(__CLASS__, 'updateNotice'));
        }

        return array(
            'name' => 'Amazon',
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/amazon',
        );
    }

    public static function updateNotice()
    {
        $config = AmazonConfig::getInstance();

        if (!$config->option('is_active'))
        {
            return;
        }

        // Already migrated
        if ($config->option('creators_credential_id') && $config->option('creators_credential_secret'))
        {
            //return;
        }

        // Auto-dismiss: once user opens Amazon module settings page at least once
        if (\is_admin())
        {
            $page = isset($_GET['page']) ? (string) $_GET['page'] : '';
            if ($page === 'content-egg-modules--Amazon')
            {
                // mark as "seen"
                \update_option('cegg_amazon_creators_notice_seen', 1, false);
                return; // don't show notice on the settings page
            }
        }

        // If already "seen", don't show anymore
        if (\get_option('cegg_amazon_creators_notice_seen'))
        {
            return;
        }

        $settings_url = esc_url(\get_admin_url(\get_current_blog_id(), 'admin.php?page=content-egg-modules--Amazon'));

        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p>' . wp_kses(
            sprintf(
                /* translators: %s: URL to Amazon module settings page */
                __(
                    '<strong>Action required:</strong> Amazon is moving key Product Advertising API features to the new <strong>Creators API</strong>. To keep product prices and availability updates working smoothly, open <a href="%s">Amazon module settings</a> and add your Creators API <em>Credential ID</em> and <em>Credential Secret</em>.',
                    'content-egg'
                ),
                $settings_url
            ),
            array(
                'a' => array('href' => array()),
                'strong' => array(),
                'em' => array(),
            )
        ) . '</p>';
        echo '</div>';
    }

    public function getParserType()
    {
        return self::PARSER_TYPE_PRODUCT;
    }

    public function defaultTemeName()
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

        if (!empty($query_params['locale']) && AmazonLocales::getLocale($query_params['locale']))
        {
            $locale = $query_params['locale'];
        }
        else
        {
            $locale = $this->config('locale');
        }

        $associate_tag = $this->getAssociateTagForLocale($locale);

        if ($is_autoupdate)
        {
            $total = $this->config('entries_per_page_update');
        }
        else
        {
            $total = $this->config('entries_per_page');
        }

        $client = $this->getAmazonClient($locale);

        $this->configureClientForLocale($client, $locale);

        $options = $this->getCommonRequestParameters();
        $options['PartnerTag'] = $associate_tag;

        if ($asin = AmazonModule::parseAsinFromUrl($keyword))
        {
            $keyword = $asin;
        }

        if ($node = self::extractNodeId($keyword))
        {
            $options['BrowseNodeId'] = $node;
            $payload['Actor'] = "*";
            $payload['SearchIndex'] = "All";
            return $this->searchByKeyword($client, $options, $query_params, $total);
        }

        if (TextHelper::isAsin($keyword) && !TextHelper::isEan($keyword))
        {
            $options['ItemIds'] = TextHelper::splitAsins($keyword);

            return $this->searchByASINs($client, $options);
        }
        else
        {
            if ($this->config('title'))
            {
                $options['Title'] = $keyword;
            }
            else
            {
                $options['Keywords'] = $keyword;
            }

            return $this->searchByKeyword($client, $options, $query_params, $total);
        }
    }

    protected function getCommonRequestParameters()
    {
        $options = array();
        $options['Resources'] = self::getResources();

        if ($v = $this->config('CurrencyOfPreference'))
        {
            $options['CurrencyOfPreference'] = $v;
        }
        if ($v = $this->config('LanguagesOfPreference'))
        {
            $options['LanguagesOfPreference'] = TextHelper::getArrayFromCommaList($v);
        }

        return $options;
    }

    protected function searchByASINs($client, array $options)
    {
        $data = $client->GetItems($options);
        if ($this->isCreatorsClient($client))
        {
            $data = $this->normalizeCreatorsResponse($data);
        }

        if (!is_array($data) || !isset($data['ItemsResult']['Items']))
        {
            return array();
        }

        $items = $data['ItemsResult']['Items'];
        $this->addVariationSummary($client, $options, $items);

        return $this->prepareResults($items, $client->getLocale(), $client->getAssociateTag());
    }

    protected function addVariationSummary($client, array $options, array &$items)
    {
        if (isset($options['ItemIds']))
        {
            unset($options['ItemIds']);
        }
        $options['Resources'] = array('VariationSummary.Price.LowestPrice');

        // check for parent ASINs
        foreach ($items as $i => $item)
        {
            // child or parent?
            if (isset($item['ParentASIN']))
            {
                continue;
            }

            // Creators API expects lowerCamelCase request params.
            // Pass "Asin" (so payload normalizer turns it into "asin").
            if ($this->isCreatorsClient($client))
            {
                $options['Asin'] = $item['ASIN'];
                unset($options['ASIN']);
            }
            else
            {
                $options['ASIN'] = $item['ASIN'];
                unset($options['Asin']);
            }

            if (\apply_filters('cegg_amazon_one_request_per_second', true))
                sleep(1);

            try
            {
                $data = $client->GetVariations($options);
            }
            catch (\Exception $e)
            {
                continue;
            }

            if ($this->isCreatorsClient($client))
            {
                $data = $this->normalizeCreatorsResponse($data);
            }

            if (isset($data['VariationsResult']['VariationSummary']))
            {
                $items[$i]['VariationSummary'] = $data['VariationsResult']['VariationSummary'];
            }
        }
    }

    protected function searchByKeyword($client, array $options, array $query_params, $total)
    {
        if (!empty($query_params['minimum_price']))
        {
            $options['MinPrice'] = TextHelper::pricePenniesDenomination($query_params['minimum_price']);
        }
        elseif ($this->config('minimum_price'))
        {
            $options['MinPrice'] = TextHelper::pricePenniesDenomination($this->config('minimum_price'));
        }
        if (!empty($query_params['maximum_price']))
        {
            $options['MaxPrice'] = TextHelper::pricePenniesDenomination($query_params['maximum_price']);
        }
        elseif ($this->config('maximum_price'))
        {
            $options['MaxPrice'] = TextHelper::pricePenniesDenomination($this->config('maximum_price'));
        }
        if (!empty($query_params['min_percentage_off']))
        {
            $options['MinSavingPercent'] = (int) $query_params['min_percentage_off'];
        }
        elseif ($this->config('min_percentage_off'))
        {
            $options['MinSavingPercent'] = (int) $this->config('min_percentage_off');
        }

        // from autoblog
        if (isset($query_params['product_condition']) && in_array($query_params['product_condition'], array(
            'new',
            'used',
            'refurbished'
        )))
        {
            $options['Condition'] = ucfirst($query_params['product_condition']);
        }
        elseif ($v = $this->config('Condition'))
        {
            $options['Condition'] = $v;
        }

        // v4 module options mapping
        if (empty($options['SearchIndex']) && $v = $this->config('search_index'))
        {
            $options['SearchIndex'] = $v;
        }
        if (empty($options['BrowseNodeId']) && $v = $this->config('brouse_node'))
        {
            $options['BrowseNodeId'] = $v;
        }

        // DeliveryFlags
        $flags = array('AmazonGlobal', 'FreeShipping', 'FulfilledByAmazon', 'Prime');
        $DeliveryFlags = array();
        foreach ($flags as $flag)
        {
            if ($this->config($flag))
            {
                $DeliveryFlags[] = $flag;
            }
        }
        if ($DeliveryFlags)
        {
            $options['DeliveryFlags'] = $DeliveryFlags;
        }

        if ($v = $this->config('MinReviewsRating'))
        {
            $options['MinReviewsRating'] = (int) $v;
        }
        if ($v = $this->config('SortBy'))
        {
            $options['SortBy'] = $this->config('SortBy');
        }

        // Guard: nothing to fetch
        $total = (int) $total;
        if ($total <= 0)
        {
            return [];
        }

        // Determine per-page (1..10). If $total <= 10, request exactly that many per page.
        $perPage = 10;
        if ($total <= 10)
        {
            $perPage = max(1, $total);
            $options['ItemCount'] = $perPage + 1; // looks like a PA API bug — it returns one fewer item than requested
        }

        $maxApiPages   = 10; // hard limit for SearchItems
        $pagesToFetch  = (int) ceil($total / $perPage);
        $pagesToFetch  = min($pagesToFetch, $maxApiPages);

        $results = [];
        for ($i = 0; $i < $pagesToFetch; $i++)
        {
            if ($i > 0 && \apply_filters('cegg_amazon_one_request_per_second', true))
            {
                sleep(1);
            }

            $options['ItemPage'] = $i + 1;

            $resp = $client->SearchItems($options);
            if ($this->isCreatorsClient($client))
            {
                $resp = $this->normalizeCreatorsResponse($resp);
            }

            if (!is_array($resp) || empty($resp['SearchResult']['Items']))
            {
                break;
            }

            // Derive available pages from API response (and cap it)
            if (isset($resp['SearchResult']['TotalResultCount']))
            {
                $availablePages = (int) ceil(((int) $resp['SearchResult']['TotalResultCount']) / $perPage);
                $availablePages = min($availablePages, $maxApiPages);
            }
            else
            {
                $availablePages = 1;
            }

            // Don’t exceed requested total
            $items     = $resp['SearchResult']['Items'];
            $remaining = $total - count($results);
            $items     = array_slice($items, 0, max(0, $remaining));

            $results = array_merge(
                $results,
                $this->prepareResults($items, $client->getLocale(), $client->getAssociateTag())
            );

            // Stop if we hit our goal or the last available page
            if (count($results) >= $total || ($i + 1) >= $availablePages)
            {
                break;
            }
        }

        return $results;
    }

    public function doRequestItems(array $items)
    {
        $locales = array();
        $default_locale = $this->config('locale');

        // find all locales
        foreach ($items as $item)
        {
            if (!empty($item['extra']['locale']))
            {
                $locale = $item['extra']['locale'];
            }
            else
            {
                $locale = $default_locale;
                $item['extra']['locale'] = $locale;
            }

            if (!in_array($locale, $locales))
            {
                $locales[] = $locale;
            }
        }

        // request by locale
        $results = array();
        foreach ($locales as $locale)
        {
            $request = array();
            foreach ($items as $item)
            {
                if ($item['extra']['locale'] == $locale)
                {
                    $request[] = $item;
                }
            }

            // This parameter can have a maximum of 10 values.
            $pages_count = ceil(count($request) / 10);
            for ($i = 0; $i < $pages_count; $i++)
            {
                // If your application is submitting requests faster than once per second per IP address, you may receive error messages from the Product Advertising API until you decrease the rate of your requests.
                if ($i > 0 && \apply_filters('cegg_amazon_one_request_per_second', true))
                    sleep(1);

                $request10 = array_slice($request, $i * 10, 10);
                try
                {
                    $results = array_merge($results, $this->requestItems($request10, $locale));
                }
                catch (\Exception $e)
                {
                    // API error. Do not update.
                    return $items;
                }
            }
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
                'currency',
                'currencyCode',
                'availability',
                'orig_url',
                'stock_status',
                'url'
            );
            foreach ($fields as $field)
            {
                $items[$unique_id][$field] = $result->$field;
            }
            if (!$this->config('save_img'))
            {
                $items[$unique_id]['img'] = $result->img;
            }

            // all extra fields for API data only
            $items[$unique_id]['extra'] = ArrayHelper::object2Array($result->extra);
        }

        return $items;
    }

    private function requestItems(array $items, $locale)
    {
        $associate_tag = $this->getAssociateTagForLocale($locale);

        $client = $this->getAmazonClient($locale);
        $this->configureClientForLocale($client, $locale);

        if ($this->isCreatorsClient($client) && method_exists($client, 'setCredentialVersion'))
        {
            $client->setCredentialVersion(AmazonLocales::getCreatorsCredentialVersion($locale));
        }

        $options = $this->getCommonRequestParameters();
        $options['PartnerTag'] = $associate_tag;

        $item_ids = array();
        foreach ($items as $item)
        {
            $item_ids[] = $item['extra']['ASIN'];
        }

        $options['ItemIds'] = $item_ids;

        return $this->searchByASINs($client, $options);
    }

    private function prepareResults($results, $locale, $associate_tag)
    {
        $data = array();
        foreach ($results as $key => $r)
        {
            $data[] = $this->prepareResult($r, $locale, $associate_tag);
        }
        return $data;
    }

    private function prepareResult($r, $locale, $associate_tag)
    {
        $content = new ContentProduct;
        $extra = new ExtraDataAmazon;

        $content->unique_id = $locale . '-' . $r['ASIN'];
        $content->url = $r['DetailPageURL'];
        $content->orig_url = strtok($r['DetailPageURL'], '?');
        $content->title = $r['ItemInfo']['Title']['DisplayValue'];

        if (isset($r['Images']['Primary']))
        {
            $content->img = $r['Images']['Primary']['Large']['URL'];
            $extra->primaryImages = $r['Images']['Primary'];
        }
        elseif (isset($r['Images']['Variants'][0]))
        {
            $content->img = $r['Images']['Variants'][0]['Large']['URL'];
        }
        $content->domain = AmazonConfig::getDomainByLocale($locale);
        $content->merchant = ucfirst($content->domain);
        $extra->ASIN = $r['ASIN'];
        $extra->locale = $locale;
        $extra->associate_tag = $associate_tag;
        $extra->addToCartUrl = $this->generateAddToCartUrl($locale, $extra->ASIN);

        $byLine = $r['ItemInfo']['ByLineInfo'] ?? [];
        $brand        = $byLine['Brand']['DisplayValue'] ?? '';
        $manufacturer = $byLine['Manufacturer']['DisplayValue'] ?? '';
        $source = apply_filters(
            'cegg_amazon_manufacturer_source',
            'manufacturer',
            $r,
            [
                'brand'        => $brand,
                'manufacturer' => $manufacturer,
            ]
        );
        $source = is_string($source) ? strtolower(trim($source)) : 'manufacturer';
        if (!in_array($source, ['manufacturer', 'brand'], true))
        {
            $source = 'manufacturer';
        }
        $value = ($source === 'brand') ? $brand : $manufacturer;
        if ($value === '')
        {
            $value = $manufacturer ?: $brand;
        }

        // Let user override/modify the final value
        $value = apply_filters(
            'cegg_amazon_manufacturer_value',
            $value,
            $source,
            $r,
            [
                'brand'        => $brand,
                'manufacturer' => $manufacturer,
            ]
        );

        if ($value !== '')
        {
            $content->manufacturer = $value;
        }

        if (isset($r['ItemInfo']['Classifications']['Binding']))
        {
            $content->category = $r['ItemInfo']['Classifications']['Binding']['DisplayValue'];
        }

        $content->categoryPath = array();
        $browseNodes = null;
        if (isset($r['BrowseNodeInfo']['BrowseNodes']) && is_array($r['BrowseNodeInfo']['BrowseNodes']))
        {
            $browseNodes = $r['BrowseNodeInfo']['BrowseNodes'];
        }
        elseif (isset($r['BrowseNodes']) && is_array($r['BrowseNodes']))
        {
            $browseNodes = $r['BrowseNodes'];
        }

        if ($browseNodes)
        {
            $mainNode = reset($browseNodes);
            $content->categoryPath = $this->extractBrowseNodePathForBreadcrumb($mainNode);
            if (empty($content->category) && !empty($content->categoryPath))
            {
                $content->category = end($content->categoryPath);
            }
        }

        if (isset($r['ItemInfo']['ExternalIds']['EANs']))
        {
            $content->ean = $r['ItemInfo']['ExternalIds']['EANs']['DisplayValues'][0];
            $extra->EANs = $r['ItemInfo']['ExternalIds']['EANs']['DisplayValues'];
        }
        if (isset($r['ItemInfo']['ExternalIds']['UPCs']))
        {
            $content->upc = $r['ItemInfo']['ExternalIds']['UPCs']['DisplayValues'][0];
            $extra->UPCs = $r['ItemInfo']['ExternalIds']['UPCs']['DisplayValues'];
        }
        if ($this->config('link_type') == 'add_to_cart')
        {
            $content->orig_url = $content->url;
            $content->url = $extra->addToCartUrl;
        }

        if (isset($r['ItemInfo']['Features']['DisplayValues']))
        {
            $features = $r['ItemInfo']['Features']['DisplayValues'];
            if ($features && is_array($features))
                $content->description = "<ul>\n<li>" . join("</li>\n<li>", $features) . "</li>\n</ul>";
        }

        if (!empty($r['Images']['Variants']))
        {
            $extra->imageSet = array();
            foreach ($r['Images']['Variants'] as $v)
            {
                $image = array(
                    'ThumbnailImage' => $v['Small']['URL'],
                    'MediumImage' => $v['Medium']['URL'],
                    'LargeImage' => $v['Large']['URL'],
                );
                $extra->imageSet[] = $image;
            }
        }

        $content->images = array();
        if ($extra->imageSet && is_array($extra->imageSet))
        {
            foreach ($extra->imageSet as $key => $image)
            {
                $content->images[] = $image['LargeImage'];
            }
        }

        $content->features = array();
        $infos = array();
        if (isset($r['ItemInfo']['ManufactureInfo']))
        {
            $infos = $r['ItemInfo']['ManufactureInfo'];
        }
        if (isset($r['ItemInfo']['ProductInfo']))
        {
            $infos = array_merge($infos, $r['ItemInfo']['ProductInfo']);
        }
        if (isset($r['ItemInfo']['ContentInfo']))
        {
            $infos = array_merge($infos, $r['ItemInfo']['ContentInfo']);
        }
        if (isset($r['ItemInfo']['TechnicalInfo']))
        {
            $infos = array_merge($infos, $r['ItemInfo']['TechnicalInfo']);
        }
        foreach ($infos as $info)
        {
            if (!isset($info['Label']) || in_array($info['Label'], array('UnitCount', 'NumberOfItems')))
            {
                continue;
            }
            if (isset($info['DisplayValue']))
            {
                $value = $info['DisplayValue'];
            }
            elseif (isset($info['DisplayValues'][0]['DisplayValue']))
            {
                $value = $info['DisplayValues'][0]['DisplayValue'];
            }
            elseif (isset($info['DisplayValues'][0]) && !is_array($info['DisplayValues'][0]))
            {
                $value = $info['DisplayValues'][0];
            }
            else
            {
                continue;
            }

            $feature = array(
                'name' => TemplateHelper::splitAttributeName($info['Label']),
                'value' => $value,
            );
            $content->features[] = $feature;
        }

        self::fillOfferVars($r, $content, $extra);
        $content->extra = $extra;

        return $content;
    }

    private function getAmazonClient($locale = null)
    {
        $mode = $this->resolveApiMode();

        if ($mode === 'creators')
        {
            // Creators credentials are per region group (NA/EU/FE), so cache one
            // client per group and pick it by the requested locale's region.
            $locale = $locale ?: (string) ($this->config('locale') ?: 'us');
            $group  = AmazonLocales::getCreatorsRegionGroup($locale);

            if (!isset($this->api_clients_creators[$group]))
            {
                $cred = $this->getCreatorsCredential($locale);
                $associate_tag = (string) $this->config('associate_tag');

                if ($cred['id'] === '' || $cred['secret'] === '')
                {
                    throw new \Exception(esc_html(sprintf(
                        /* translators: %s: Amazon Creators region group (NA/EU/FE) */
                        __('Creators API credentials are not configured for the %s region. Please enter a Credential ID and Secret for this region (or a Primary credential) in module settings.', 'content-egg'),
                        $group
                    )));
                }

                $client = new AmazonCreatorsApi($cred['id'], $cred['secret'], $associate_tag, $locale);
                $client->setMajorVersion($this->resolveCreatorsMajorVersion($cred['id']));
                $this->api_clients_creators[$group] = $client;
            }

            return $this->api_clients_creators[$group];
        }

        // Default/fallback: PA-API 5.0
        if ($this->api_client_paapi5 === null)
        {
            $access_key_id = (string) $this->config('access_key_id');
            $secret_access_key = (string) $this->config('secret_access_key');
            $associate_tag = (string) $this->config('associate_tag');

            if ($access_key_id === '' || $secret_access_key === '')
            {
                throw new \Exception(esc_html__('PA-API 5.0 credentials are not configured. Please enter Access Key and Secret Key in module settings (or switch to Creators API).', 'content-egg'));
            }

            $this->api_client_paapi5 = new AmazonApi($access_key_id, $secret_access_key, $associate_tag);
        }

        return $this->api_client_paapi5;
    }

    /**
     * Resolve the Creators credential (id/secret) to use for a locale, choosing the
     * locale's region-specific pair and falling back to the Primary pair.
     *
     * @param string $locale
     * @return array{id:string, secret:string, group:string}
     */
    private function getCreatorsCredential($locale)
    {
        return AmazonLocales::getCreatorsCredentialForLocale($locale, $this->creatorsConfigSnapshot());
    }

    /**
     * Snapshot of the module's Creators credential options (Primary + per-region),
     * shaped for AmazonLocales::getCreatorsCredentialForLocale().
     *
     * @return array<string,string>
     */
    private function creatorsConfigSnapshot()
    {
        return array(
            'creators_credential_id'        => (string) $this->config('creators_credential_id'),
            'creators_credential_secret'    => (string) $this->config('creators_credential_secret'),
            'creators_credential_id_na'     => (string) $this->config('creators_credential_id_na'),
            'creators_credential_secret_na' => (string) $this->config('creators_credential_secret_na'),
            'creators_credential_id_eu'     => (string) $this->config('creators_credential_id_eu'),
            'creators_credential_secret_eu' => (string) $this->config('creators_credential_secret_eu'),
            'creators_credential_id_fe'     => (string) $this->config('creators_credential_id_fe'),
            'creators_credential_secret_fe' => (string) $this->config('creators_credential_secret_fe'),
        );
    }

    private function resolveApiMode()
    {
        $mode = (string) $this->config('api_mode');
        if ($mode === '')
        {
            $mode = 'auto';
        }

        if ($mode === 'auto')
        {
            // Prefer Creators if configured, otherwise fall back to PA-API 5.0.
            if ($this->isCreatorsConfigured())
            {
                return 'creators';
            }
            return 'paapi5';
        }

        if ($mode !== 'creators' && $mode !== 'paapi5')
        {
            return 'auto';
        }

        return $mode;
    }

    private function isCreatorsConfigured()
    {
        $snap = $this->creatorsConfigSnapshot();
        foreach (array('', '_na', '_eu', '_fe') as $suffix)
        {
            if ($snap['creators_credential_id' . $suffix] !== '' && $snap['creators_credential_secret' . $suffix] !== '')
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Resolve Creators API major version ('v2' or 'v3') from config or auto-detection.
     *
     * @param string|null $credential_id
     * @return string 'v2' or 'v3'
     */
    private function resolveCreatorsMajorVersion($credential_id = null)
    {
        $configured = (string) $this->config('creators_credential_version');

        if ($configured === 'v2' || $configured === 'v3')
        {
            return $configured;
        }

        // Auto-detect from credential ID format
        if ($credential_id === null)
        {
            $credential_id = (string) $this->config('creators_credential_id');
        }

        return AmazonLocales::detectCredentialMajorVersion($credential_id);
    }

    private function isCreatorsClient($client)
    {
        return ($client instanceof AmazonCreatorsApi);
    }

    private static function normalizeCreatorsResponse(array $data): array
    {
        // Creators responses are lowerCamelCase; PA-API parsing expects UpperCamelCase + acronyms.
        // This will convert the whole response tree, including top-level envelopes:
        // itemsResult -> ItemsResult, searchResult -> SearchResult, variationsResult -> VariationsResult, etc.
        return self::mapCreatorsArrayToPaApi($data);
    }

    /**
     * Recursively maps Creators API response arrays to PA-API-like casing/keys.
     *
     * @param mixed $value
     * @return mixed
     */
    private static function mapCreatorsArrayToPaApi($value)
    {
        if (!is_array($value))
        {
            return $value;
        }

        $out = array();

        foreach ($value as $k => $v)
        {
            // Preserve numeric indexes (lists)
            if (is_int($k))
            {
                $out[$k] = self::mapCreatorsArrayToPaApi($v);
                continue;
            }

            $newKey = self::mapCreatorsKeyToPaApi((string) $k);
            $out[$newKey] = self::mapCreatorsArrayToPaApi($v);
        }

        return $out;
    }

    private static function mapCreatorsKeyToPaApi(string $key): string
    {
        // Fast path: exact matches for acronyms / special keys.
        static $map = array(
            // Core IDs / URLs
            'asin'         => 'ASIN',
            'parentASIN'   => 'ParentASIN',
            'detailPageURL' => 'DetailPageURL',
            'url'          => 'URL',

            // External IDs (Creators may use different casing; normalize)
            'eans'         => 'EANs',
            'EANs'         => 'EANs',
            'upcs'         => 'UPCs',
            'UPCs'         => 'UPCs',
            'gtins'        => 'GTINs',
            'GTINs'        => 'GTINs',

            // Common objects
            'itemInfo'        => 'ItemInfo',
            'byLineInfo'      => 'ByLineInfo',
            'browseNodeInfo'  => 'BrowseNodeInfo',
            'customerReviews' => 'CustomerReviews',
            'offersV2'        => 'OffersV2',

            // Images
            'hiRes' => 'HiRes',

            // Results envelopes / fields
            'itemsResult'      => 'ItemsResult',
            'searchResult'     => 'SearchResult',
            'variationsResult' => 'VariationsResult',

            // Lists / counts
            'itemIds'   => 'ItemIds',
            'itemIdType' => 'ItemIdType',
        );

        if (isset($map[$key]))
        {
            return $map[$key];
        }

        // Generic: Creators lowerCamelCase -> UpperCamelCase.
        // This preserves existing internal acronyms (e.g. violatesMAP => ViolatesMAP).
        // It will also convert already-capitalized keys safely (e.g. 'Money' stays 'Money').
        return ucfirst($key);
    }

    /**
     * Override token caching to support multiple Amazon Creators regions (NA/EU/FE)
     * and avoid collisions if user changes credentials.
     *
     * @param bool $force
     * @param string|null $locale
     * @return string
     */
    public function getAccessToken($force = false, $locale = null)
    {
        $locale = $locale ?: (string) ($this->config('locale') ?: 'us');

        $suffix = $this->getCreatorsTokenCacheKeySuffix($locale);
        $transient_name = Plugin::slug() . '-' . $this->getId() . '-access_token-' . $suffix;

        $token = \get_transient($transient_name);

        if (!$token || $force)
        {
            list($token, $expires_in) = $this->requestAccessToken($locale);

            // Refresh a bit earlier than actual expiry
            $ttl = (int) $expires_in;
            if ($ttl > 120)
            {
                $ttl = $ttl - 60;
            }

            \set_transient($transient_name, $token, $ttl);
        }

        return $token;
    }

    private function getCreatorsTokenCacheKeySuffix($locale)
    {
        $group = AmazonLocales::getCreatorsRegionGroup($locale);
        $id = $this->getCreatorsCredential($locale)['id'];
        $major = $this->resolveCreatorsMajorVersion($id);

        $id_hash = $id !== '' ? substr(md5($id), 0, 10) : 'noid';

        return strtolower($group) . '-' . $major . '-' . $id_hash;
    }

    /**
     * Called by getAccessToken().
     *
     * @param string|null $locale
     * @return array [token, expires_in]
     */
    public function requestAccessToken($locale = null)
    {
        $locale = $locale ?: (string) ($this->config('locale') ?: 'us');

        $cred = $this->getCreatorsCredential($locale);
        $credential_id = $cred['id'];
        $credential_secret = $cred['secret'];
        $associate_tag = (string) $this->config('associate_tag');

        $api_client = new AmazonCreatorsApi($credential_id, $credential_secret, $associate_tag, $locale);
        $api_client->setMajorVersion($this->resolveCreatorsMajorVersion($credential_id));

        try
        {
            $response = $api_client->requestAccessToken();
        }
        catch (\Exception $e)
        {
            $raw = (string) $e->getMessage();

            if (strpos($raw, 'invalid_client') !== false || strpos($raw, '{"error":"invalid_client"}') !== false)
            {
                $msg = __(
                    'Amazon Creators API: Invalid client credentials. Please verify your Credential ID and Credential Secret. This can also happen if you created credentials for a different region/locale (NA/EU/FE). Make sure your Amazon module locale matches the region where the credentials were created, or generate new Creators API credentials for the correct region in Associates Central.',
                    'content-egg'
                );

                throw new \Exception(esc_html($msg), (int) $e->getCode());
            }

            throw $e;
        }

        if (empty($response['access_token']) || empty($response['expires_in']))
        {
            throw new \Exception('Amazon Creators API: Invalid token response format.');
        }

        return array((string) $response['access_token'], (int) $response['expires_in']);
    }

    private function configureClientForLocale($client, $locale)
    {
        $associate_tag = $this->getAssociateTagForLocale($locale);

        $client->setLocale($locale);
        $client->setAssociateTag($associate_tag);

        if ($this->isCreatorsClient($client))
        {
            // Inject cached token (or request a new one if needed)
            $client->setAccessToken($this->getAccessToken(false, $locale));
        }
    }

    private function fillOfferVars($r, $content, $extra)
    {
        $content->price = 0.0;
        $content->priceOld = 0.0;
        $content->currencyCode = null;
        $content->stock_status = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
        unset($extra->lowestNewPrice, $extra->lowestUsedPrice, $extra->totalNew, $extra->totalUsed);

        $price_mode = $this->config('price_mode');

        $listing = null;

        if (!empty($r['OffersV2']['Listings']) && is_array($r['OffersV2']['Listings']))
        {
            $listings = $r['OffersV2']['Listings'];

            if ($price_mode === 'prime')
            {
                foreach ($listings as $l)
                {
                    if (($l['DealDetails']['AccessType'] ?? null) === 'PRIME_EXCLUSIVE')
                    {
                        $listing = $l;
                        break;
                    }
                }
                if (!$listing)
                {
                    foreach ($listings as $l)
                    {
                        if (($l['DealDetails']['AccessType'] ?? null) === 'PRIME_EARLY_ACCESS')
                        {
                            $listing = $l;
                            break;
                        }
                    }
                }
                if (!$listing)
                {
                    foreach ($listings as $l)
                    {
                        if (filter_var($l['IsBuyBoxWinner'] ?? false, FILTER_VALIDATE_BOOLEAN))
                        {
                            $listing = $l;
                            break;
                        }
                    }
                }
                if (!$listing)
                {
                    $listing = $listings[0];
                }
            }
            elseif ($price_mode === 'all')
            {
                foreach ($listings as $l)
                {
                    $access = $l['DealDetails']['AccessType'] ?? 'ALL';
                    if ($access === 'ALL')
                    {
                        $listing = $l;
                        break;
                    }
                }
                if (!$listing)
                {
                    foreach ($listings as $l)
                    {
                        if (filter_var($l['IsBuyBoxWinner'] ?? false, FILTER_VALIDATE_BOOLEAN))
                        {
                            $listing = $l;
                            break;
                        }
                    }
                }
                if (!$listing)
                {
                    $listing = $listings[0];
                }
            }
            else
            {
                foreach ($listings as $l)
                {
                    if (filter_var($l['IsBuyBoxWinner'] ?? false, FILTER_VALIDATE_BOOLEAN))
                    {
                        $listing = $l;
                        break;
                    }
                }
                if (!$listing)
                {
                    $listing = $listings[0];
                }
            }
        }

        if ($listing)
        {
            if (!empty($listing['Availability']))
            {
                $availabilityType = $listing['Availability']['Type'] ?? null;
                $content->availability = $listing['Availability']['Message'] ?? null;

                $extra->AvailabilityType = $availabilityType;
                $extra->AvailabilityMessage = $listing['Availability']['Message'] ?? null;
                if (isset($listing['Availability']['MinOrderQuantity'])) $extra->MinOrderQuantity = (int) $listing['Availability']['MinOrderQuantity'];
                if (isset($listing['Availability']['MaxOrderQuantity'])) $extra->MaxOrderQuantity = (int) $listing['Availability']['MaxOrderQuantity'];

                $inStockValues  = array('IN_STOCK', 'IN_STOCK_SCARCE', 'PREORDER', 'AVAILABLE_DATE', 'LEADTIME');
                $outStockValues = array('OUT_OF_STOCK', 'UNAVAILABLE');

                if (in_array($availabilityType, $inStockValues, true))
                {
                    $content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
                }
                elseif (in_array($availabilityType, $outStockValues, true))
                {
                    $content->stock_status = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                }
            }

            if (!empty($listing['Price']['Money']))
            {
                $content->price = (float) ($listing['Price']['Money']['Amount'] ?? 0);
                $content->currencyCode = $listing['Price']['Money']['Currency'] ?? null;
                $extra->DisplayAmount = $listing['Price']['Money']['DisplayAmount'] ?? null;
            }

            if (!empty($listing['Price']['SavingBasis']['Money']))
            {
                $content->priceOld = (float) ($listing['Price']['SavingBasis']['Money']['Amount'] ?? 0);
                $extra->SavingBasisAmount = isset($listing['Price']['SavingBasis']['Money']['Amount']) ? (float) $listing['Price']['SavingBasis']['Money']['Amount'] : null;
                $extra->SavingBasisCurrency = $listing['Price']['SavingBasis']['Money']['Currency'] ?? null;
                $extra->SavingBasisDisplayAmount = $listing['Price']['SavingBasis']['Money']['DisplayAmount'] ?? null;
                $extra->SavingBasisType = $listing['Price']['SavingBasis']['SavingBasisType'] ?? null;
                $extra->SavingBasisTypeLabel = $listing['Price']['SavingBasis']['SavingBasisTypeLabel'] ?? null;
            }

            if (!empty($listing['Price']['Savings']))
            {
                $extra->SavingsAmount = isset($listing['Price']['Savings']['Money']['Amount']) ? (float) $listing['Price']['Savings']['Money']['Amount'] : null;
                if (!empty($listing['Price']['Savings']['Percentage']))
                {
                    $extra->SavingsPercentage = (int) $listing['Price']['Savings']['Percentage'];
                }
                if (!empty($listing['Price']['Savings']['Money']['DisplayAmount']))
                {
                    $extra->SavingsDisplay = $listing['Price']['Savings']['Money']['DisplayAmount'];
                }
            }

            if (!empty($listing['Price']['PricePerUnit']))
            {
                $ppu = $listing['Price']['PricePerUnit'];
                if (isset($ppu['Amount']))   $extra->PricePerUnitAmount = (float) $ppu['Amount'];
                if (isset($ppu['Currency'])) $extra->PricePerUnitCurrency = $ppu['Currency'];
                if (!empty($ppu['DisplayAmount'])) $extra->pricePerUnitDisplay = $ppu['DisplayAmount'];
            }
            elseif (!empty($extra->DisplayAmount) && preg_match('/\(([^)]+)\)/', $extra->DisplayAmount, $m))
            {
                $extra->pricePerUnitDisplay = $m[1];
            }

            $extra->IsBuyBoxWinner = filter_var($listing['IsBuyBoxWinner'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $extra->ViolatesMAP    = filter_var($listing['ViolatesMAP']    ?? false, FILTER_VALIDATE_BOOLEAN);
            $extra->Condition      = $listing['Condition']['Value']        ?? null;
            $extra->SubCondition   = $listing['Condition']['SubCondition'] ?? null;
            $extra->ConditionNote  = $listing['Condition']['ConditionNote'] ?? null;
            $extra->MerchantName   = $listing['MerchantInfo']['Name']      ?? null;
            $extra->MerchantId     = $listing['MerchantInfo']['Id']        ?? null;
            $extra->ListingType    = $listing['Type'] ?? ($listing['ListingType'] ?? null);

            if (!empty($listing['DealDetails']))
            {
                $extra->DealAccessType  = $listing['DealDetails']['AccessType'] ?? null;
                $extra->DealBadge       = $listing['DealDetails']['Badge']      ?? null;
                $content->promo         = $extra->DealBadge;
                $extra->DealStartTime   = $listing['DealDetails']['StartTime']  ?? null;
                $extra->DealEndTime     = $listing['DealDetails']['EndTime']    ?? null;
                if (isset($listing['DealDetails']['PercentClaimed']))
                {
                    $extra->DealPercentClaimed = (int) $listing['DealDetails']['PercentClaimed'];
                }
                if (isset($listing['DealDetails']['EarlyAccessDurationInMilliseconds']))
                {
                    $extra->DealEarlyAccessDurationInMilliseconds = (int) $listing['DealDetails']['EarlyAccessDurationInMilliseconds'];
                }
                $extra->IsPrimeExclusive   = ($extra->DealAccessType === 'PRIME_EXCLUSIVE');
                $extra->IsPrimeEarlyAccess = ($extra->DealAccessType === 'PRIME_EARLY_ACCESS');
                $extra->IsPrimeEligible    = in_array($extra->DealAccessType, array('PRIME_EARLY_ACCESS', 'PRIME_EXCLUSIVE'), true);
            }

            $extra->priorityListing = ($price_mode !== 'buybox' && !$extra->IsBuyBoxWinner);

            if ($content->price > 0 && $content->stock_status === ContentProduct::STOCK_STATUS_OUT_OF_STOCK)
            {
                $content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
            }
        }

        if (!$content->price && !empty($r['VariationSummary']['Price']['LowestPrice']))
        {
            $lp = $r['VariationSummary']['Price']['LowestPrice'];
            $content->price = (float) ($lp['Amount'] ?? 0);
            $content->currencyCode = $lp['Currency'] ?? $content->currencyCode;
            $content->priceOld = 0.0;
            $content->stock_status = ContentProduct::STOCK_STATUS_IN_STOCK;
        }
    }

    public function getAssociateTagForLocale(string $locale)
    {
        $defaultLocale = (string) $this->config('locale');

        if ($locale === $defaultLocale)
        {
            return $this->config('associate_tag');
        }

        $key = 'associate_tag_' . $locale;

        $tag = $this->config($key);
        if ($tag === null || $tag === '')
        {
            $tag = $this->config('associate_tag');
        }

        return $tag;
    }

    /**
     * Add to shopping cart url
     * @link: https://webservices.amazon.com/paapi5/documentation/add-to-cart-form.html
     * @link: https://affiliate-program.amazon.com/help/node/topic/G9SMD8TQHFJ7728F
     */
    private function getAmazonAddToCartUrl($locale)
    {
        return 'https://www.' . AmazonConfig::getDomainByLocale($locale) . '/gp/aws/cart/add.html';
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

    public static function parseAsinFromUrl($url)
    {
        $regex = '~/(?:exec/obidos/ASIN/|o/|gp/product/|gp/offer-listing/|(?:(?:[^"\'/]*)/)?dp/|)([0-9A-Z]{10})(?:(?:/|\?|\#)(?:[^"\'\s]*))?~isx';
        if (preg_match($regex, $url, $matches))
        {
            return $matches[1];
        }
        else
        {
            return false;
        }
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
            if (empty($data[$key]['merchant']) || $data[$key]['merchant'] == 'Amazon')
            {
                $data[$key]['merchant'] = ucfirst($d['domain']);
            }

            if ($this->config('link_type') == 'product' && strstr($d['url'], 'AssociateTag=') && !empty($d['orig_url']))
            {
                $data[$key]['url'] = $d['orig_url'];
            }
            elseif ($this->config('link_type') == 'add_to_cart' && !strstr($d['url'], 'AssociateTag=') && !empty($d['extra']['addToCartUrl']))
            {
                $data[$key]['url'] = $d['extra']['addToCartUrl'];
            }

            // forced URL update
            if ($this->config('forced_urls_update'))
            {
                if ($d['extra']['locale'])
                {
                    $tag_id = $this->getAssociateTagForLocale($d['extra']['locale']);
                }
                else
                {
                    $tag_id = $this->getAssociateTagForLocale($this->config('locale'));
                }

                if (strstr($data[$key]['url'], 'AssociateTag='))
                {
                    $data[$key]['url'] = TextHelper::addUrlParam($data[$key]['url'], 'AssociateTag', $tag_id);
                }
                else
                {
                    $data[$key]['url'] = TextHelper::addUrlParam($data[$key]['url'], 'tag', $tag_id);
                }
            }

            // forced Associate Tag
            if ($forced_tag = $this->config('forced_tag'))
            {
                $data[$key]['url'] = TextHelper::addUrlParam($data[$key]['url'], 'tag', $forced_tag);
            }

            // Amazon Global Earning
            $global_earning = $this->config('global_earning');
            if ($global_earning == 'search_results')
            {
                // send to local search results instead of a similar product
                $data[$key]['url'] = TextHelper::addUrlParam($data[$key]['url'], 'gaOptInStatus', 'false');
            }
            elseif ($global_earning == 'no_redirect')
            {
                // keep the shopper in this module's store
                $data[$key]['url'] = TextHelper::addUrlParam($data[$key]['url'], 'creatorsDisableRedirect', 'true');
            }

            // hide prices?
            $hp = $this->config('hide_prices');
            if ($hp == 'hide_24')
            {
                $last_update = TemplateHelper::getLastUpdate($this->getId());
                if ($last_update)
                    $lt = time() - $last_update;
                else
                    $lt = 0;
            }
            else
                $lt = 0;

            if ($hp == 'hide' || ($hp == 'hide_24' && $lt > \DAY_IN_SECONDS))
            {
                $data[$key]['price'] = '';
                $data[$key]['priceOld'] = '';
                $data[$key]['stock_status'] = 0;
                $data[$key]['extra']['totalNew'] = '';
                $data[$key]['extra']['totalUsed'] = '';
                $data[$key]['extra']['lowestNewPrice'] = '';
                $data[$key]['extra']['lowestUsedPrice'] = '';
                $data[$key]['extra']['PricePerUnit'] = '';
            }
        }

        return parent::viewDataPrepare($data);
    }

    public static function getResources()
    {
        return array(
            // Reviews
            "CustomerReviews.Count",
            "CustomerReviews.StarRating",

            // Images
            "Images.Primary.Small",
            "Images.Primary.Medium",
            "Images.Primary.Large",
            "Images.Variants.Small",
            "Images.Variants.Medium",
            "Images.Variants.Large",

            // Item info
            "ItemInfo.ByLineInfo",
            "ItemInfo.ContentInfo",
            "ItemInfo.ContentRating",
            "ItemInfo.Classifications",
            "ItemInfo.ExternalIds",
            "ItemInfo.Features",
            "ItemInfo.ManufactureInfo",
            "ItemInfo.ProductInfo",
            "ItemInfo.TechnicalInfo",
            "ItemInfo.Title",
            "ItemInfo.TradeInInfo",

            // Browse Nodes
            "BrowseNodeInfo.BrowseNodes",
            "BrowseNodeInfo.BrowseNodes.Ancestor",
            "BrowseNodeInfo.BrowseNodes.SalesRank",
            "BrowseNodeInfo.WebsiteSalesRank",

            // Offers V2
            "OffersV2.Listings.Availability",
            "OffersV2.Listings.Condition",
            "OffersV2.Listings.DealDetails",
            "OffersV2.Listings.IsBuyBoxWinner",
            "OffersV2.Listings.LoyaltyPoints",
            "OffersV2.Listings.MerchantInfo",
            "OffersV2.Listings.Price",
            "OffersV2.Listings.Type",

            // Misc
            "ParentASIN"
        );
    }

    public static function extractNodeId($url)
    {
        $parsedUrl = parse_url($url);
        if (!$parsedUrl || !isset($parsedUrl['query']))
        {
            return null;
        }

        parse_str($parsedUrl['query'], $queryParams);
        return isset($queryParams['node']) ? $queryParams['node'] : null;
    }

    /**
     * Build category path (root -> leaf) from a BrowseNode structure
     * in a way that roughly matches Amazon's breadcrumb.
     *
     * @param array $node Single browse node (with optional nested 'Ancestor')
     * @return array
     */
    private function extractBrowseNodePathForBreadcrumb(array $node)
    {
        $path = array();

        $walker = function (array $n) use (&$walker, &$path)
        {
            // Recurse to the top ancestor first
            if (isset($n['Ancestor']) && is_array($n['Ancestor']))
            {
                $walker($n['Ancestor']);
            }

            // Prefer DisplayName (matches site UI best)
            $label = null;
            if (!empty($n['DisplayName']))
            {
                $label = $n['DisplayName'];
            }
            elseif (!empty($n['ContextFreeName']))
            {
                $label = $n['ContextFreeName'];
            }

            if ($label === null)
            {
                return;
            }

            $label = trim($label);

            // Skip generic "Categories" level
            $ignore = array('Categories');
            if (in_array($label, $ignore, true))
            {
                return;
            }

            // Avoid duplicates
            if (!in_array($label, $path, true))
            {
                $path[] = $label;
            }
        };

        $walker($node);

        return $path;
    }

    public static function getPriceParamMap()
    {
        return [
            'min' => 'minimum_price',
            'max' => 'maximum_price',
        ];
    }

    public static function getLocaleParamMap()
    {
        return 'locale';
    }

    public static function getSortByNewestParamMap()
    {
        return [
            'SortBy' => 'NewestArrivals',
        ];
    }
}
