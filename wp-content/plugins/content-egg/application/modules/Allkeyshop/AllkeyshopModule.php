<?php

namespace ContentEgg\application\modules\Allkeyshop;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModule;
use ContentEgg\application\components\ContentProduct;
use ContentEgg\application\admin\PluginAdmin;
use ContentEgg\application\helpers\TextHelper;

/**
 * AllkeyshopModule class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class AllkeyshopModule extends AffiliateParserModule
{
    const MAX_OFFERS_PER_PRODUCT = 10;

    // Offer ids sent per update request. This MUST NOT exceed
    // MAX_OFFERS_PER_PRODUCT: the response caps offers at product_offers per
    // product, so a request carrying more ids than that cap silently drops the
    // surplus whenever several of them belong to the same product - and a
    // dropped id looks exactly like a delisted offer, marking a live offer out
    // of stock on every cron run. At 10 ids the worst case (all ten belonging
    // to one product) still fits inside the cap.
    const UPDATE_CHUNK_SIZE = self::MAX_OFFERS_PER_PRODUCT;

    private $api_client = null;

    public function info()
    {
        return array(
            'name' => 'AllKeyShop',
            'description' => __('Compares CD key and game key prices across merchants.', 'content-egg'),
            'docs_uri' => 'https://ce-docs.keywordrush.com/modules/affiliate/allkeyshop',
        );
    }

    public function releaseVersion()
    {
        return '21.11.0';
    }

    public function getParserType()
    {
        return self::PARSER_TYPE_PRODUCT;
    }

    public function defaultTemplateName()
    {
        return 'grid';
    }

    public function isUrlSearchAllowed()
    {
        return true;
    }

    public function isItemsUpdateAvailable()
    {
        return true;
    }

    public function doRequest($keyword, $query_params = array(), $is_autoupdate = false)
    {
        $per_page = $is_autoupdate
            ? (int) $this->config('entries_per_page_update')
            : (int) $this->config('entries_per_page');

        $options = $this->buildQuery(array(
            'per_page' => max(1, min(100, $per_page)),
        ));

        // An AllKeyShop product page URL resolves through the link filter;
        // there is no identifier search, so EAN and ASIN are not handled.
        if (TextHelper::isUrl($keyword))
            $options['aks_links:or'] = $keyword;
        else
            $options['search_name'] = $keyword;

        if (!empty($query_params['priceFrom']))
            $options['price_min'] = (float) $query_params['priceFrom'];
        if (!empty($query_params['priceTo']))
            $options['price_max'] = (float) $query_params['priceTo'];

        $results = $this->getApiClient()->catalog($options);

        if (empty($results['products']) || !is_array($results['products']))
            return array();

        return $this->prepareResults($results['products']);
    }

    public function doRequestItems(array $items)
    {
        $ids = array();
        foreach ($items as $key => $item)
        {
            if (!empty($item['unique_id']))
                $ids[] = (string) $item['unique_id'];
        }

        if (!$ids)
            return $items;

        $fresh = array();
        foreach (array_chunk(array_unique($ids), self::UPDATE_CHUNK_SIZE) as $chunk)
        {
            foreach ($this->requestOffers($chunk) as $offer_id => $product)
                $fresh[$offer_id] = $product;
        }

        foreach ($items as $key => $item)
        {
            $unique_id = !empty($item['unique_id']) ? (string) $item['unique_id'] : '';

            if ($unique_id === '' || !isset($fresh[$unique_id]))
            {
                // The offer is gone from the catalogue entirely.
                $items[$key]['stock_status'] = ContentProduct::STOCK_STATUS_OUT_OF_STOCK;
                continue;
            }

            $product = $fresh[$unique_id];

            $items[$key]['stock_status'] = $product->stock_status;
            $items[$key]['url'] = $product->url;
            $items[$key]['merchant'] = $product->merchant;
            $items[$key]['logo'] = $product->logo;
            $items[$key]['promo'] = $product->promo;
            $items[$key]['extra']['coupon_code'] = $product->extra->coupon_code;
            $items[$key]['extra']['coupon_discount'] = $product->extra->coupon_discount;

            // A delisted offer keeps returning its last, stale price - offer
            // 764255 comes back at 172.32 having last been live at 26.02 - so
            // the stored price is left alone and only the status changes.
            if ($product->stock_status === ContentProduct::STOCK_STATUS_OUT_OF_STOCK)
                continue;

            $items[$key]['price'] = $product->price;
            $items[$key]['priceOld'] = $product->priceOld;
        }

        return $items;
    }

    /**
     * Exact lookup of specific offers, keyed by offer id.
     */
    private function requestOffers(array $offer_ids)
    {
        $options = $this->buildQuery(array(
            'offers.legacy_id:or' => implode(',', $offer_ids),
            'per_page' => 100,
            // Always the maximum, never n_offers: several saved offers can
            // belong to one product, and a lower value silently drops them,
            // which would mark them out of stock on every cron run.
            'product_offers' => self::MAX_OFFERS_PER_PRODUCT,
        ));

        $results = $this->getApiClient()->catalog($options);

        if (empty($results['products']) || !is_array($results['products']))
            return array();

        $by_offer = array();
        foreach ($results['products'] as $product)
        {
            if (empty($product['offers']))
                continue;

            $base = $this->prepareProduct($product);

            foreach ($product['offers'] as $offer)
            {
                if (empty($offer['id']))
                    continue;

                $by_offer[(string) $offer['id']] = $this->prepareOffer($product, $offer, $base);
            }
        }

        return $by_offer;
    }

    /**
     * Query parameters shared by the search and update paths.
     *
     * Search and update MUST send identical currency, locale and price_mode,
     * or the same offer comes back at a different number on every cron run.
     */
    private function buildQuery(array $overrides = array())
    {
        $options = array(
            'currency' => (string) $this->config('currency'),
            'locale' => (string) $this->config('locale'),
            'sort_field' => (string) $this->config('sort_field'),
            'sort_order' => (string) $this->config('sort_order'),
            'product_offers' => $this->getOffersPerProduct(),
        );

        if ($this->config('allow_accounts_on_games'))
            $options['allow_accounts_on_games'] = 'true';

        if ($price_mode = (string) $this->config('price_mode'))
            $options['price_mode'] = $price_mode;

        if ($country = (string) $this->config('activation_country'))
            $options['activation_country'] = $country;

        return array_merge($options, $overrides);
    }

    private function getOffersPerProduct()
    {
        $n = (int) $this->config('n_offers');

        return max(1, min(self::MAX_OFFERS_PER_PRODUCT, $n));
    }

    private function prepareResults(array $products)
    {
        $data = array();

        foreach ($products as $product)
        {
            if (empty($product['id']) || empty($product['offers']))
                continue;

            $base = $this->prepareProduct($product);

            foreach ($product['offers'] as $offer)
            {
                if (empty($offer['id']))
                    continue;

                $data[] = $this->prepareOffer($product, $offer, $base);
            }
        }

        return $data;
    }

    /**
     * Fields shared by every offer of one product. Cloned per offer.
     */
    private function prepareProduct(array $product)
    {
        $content = new ContentProduct;

        $title = (string) $product['name'];
        if (!empty($product['child_name']))
            $title .= ' ' . $product['child_name'];

        $content->title = trim($title);
        $content->currencyCode = strtoupper((string) $this->config('currency'));

        if (!empty($product['assets']['cover']))
            $content->img = $product['assets']['cover'];
        elseif (!empty($product['assets']['capsule']))
            $content->img = $product['assets']['capsule'];

        if (!empty($product['developers'][0]['name']))
            $content->manufacturer = $product['developers'][0]['name'];

        if (!empty($product['raw_category']))
            $content->category = $product['raw_category'];

        // AllKeyShop rates 0-100; ContentProduct wants a 5 point scale.
        if (!empty($product['rating']))
        {
            $content->ratingDecimal = round((float) $product['rating'] / 20, 2);
            $content->rating = TextHelper::ratingPrepare($content->ratingDecimal);
        }

        if (!empty($product['rating_details']['users']['rev_count']))
            $content->reviewsCount = (int) $product['rating_details']['users']['rev_count'];

        // Lets CE's group_pick=cheapest collapse a comparison post to one row
        // per game while still storing every offer.
        $content->group = $content->title;

        return $content;
    }

    private function prepareOffer(array $product, array $offer, ContentProduct $base)
    {
        $content = clone $base;

        $content->unique_id = (string) $offer['id'];
        $content->url = isset($offer['buy_url']) ? $offer['buy_url'] : '';
        $content->price = isset($offer['price']) ? (float) $offer['price'] : 0;
        $content->priceOld = $this->getOfficialPrice($product, $content->price);
        $content->stock_status = self::stockStatus(isset($offer['stock_status']) ? $offer['stock_status'] : '');

        if (!empty($offer['merchant']['name']))
            $content->merchant = $offer['merchant']['name'];
        if (!empty($offer['merchant']['favicon']))
            $content->logo = $offer['merchant']['favicon'];

        // If the token is granted offers.url_raw scope the merchant URL is
        // authoritative and the base class derives the domain from it, making
        // the mapping below redundant. Read it defensively so the module starts
        // doing the right thing the moment AllKeyShop widens the scope.
        if (!empty($offer['url_raw']))
            $content->orig_url = $offer['url_raw'];

        $content->domain = self::getMerchantDomain(
            isset($offer['merchant']) && is_array($offer['merchant']) ? $offer['merchant'] : array()
        );

        $coupon = self::getCoupon($offer);
        $content->promo = self::couponBadge($coupon);

        $content->extra = new ExtraDataAllkeyshop();
        $content->extra->product_id = (int) $product['id'];
        $content->extra->offer_id = (int) $offer['id'];
        $content->extra->product_type = isset($product['type']) ? $product['type'] : '';
        $content->extra->release_date = isset($product['release_date']) ? $product['release_date'] : '';
        $content->extra->pegi = isset($product['pegi']) ? $product['pegi'] : '';
        $content->extra->official_website = isset($product['official_website']) ? $product['official_website'] : '';
        $content->extra->offers_count = isset($product['offers_count']) ? (int) $product['offers_count'] : 0;
        $content->extra->region = isset($offer['region']['display_name']) ? $offer['region']['display_name'] : '';
        $content->extra->edition = isset($offer['edition']['name']) ? $offer['edition']['name'] : '';
        $content->extra->deal_score = isset($offer['deal_score']) ? (float) $offer['deal_score'] : 0;

        if (!empty($product['publishers'][0]['name']))
            $content->extra->publisher = $product['publishers'][0]['name'];

        if (!empty($product['activation_platforms'][0]['name']))
            $content->extra->activation_platform = $product['activation_platforms'][0]['name'];

        $content->extra->genres = self::getGenres($product);

        if ($coupon)
        {
            $content->extra->coupon_code = isset($coupon['code']) ? $coupon['code'] : '';
            $content->extra->coupon_discount = isset($coupon['discount']) ? (float) $coupon['discount'] : 0;
        }

        return $content;
    }

    /**
     * priceOld comes from the official store offer, because the token cannot
     * read offers._old_price. Absent for some products, in which case there is
     * no discount to show.
     */
    private function getOfficialPrice(array $product, $price)
    {
        if (empty($product['official_offers'][0]['price']))
            return 0;

        $official = (float) $product['official_offers'][0]['price'];

        return ($official > $price) ? $official : 0;
    }

    /**
     * Merchant slug to storefront domain.
     *
     * Keyed on slug rather than name because the slug is stable and already
     * normalised, while names carry casing and regional suffixes - "GameBillet
     * US" and "Gamesplanet US" both describe one storefront.
     *
     * The slug is not itself a domain ("royal-cd-keys" is royalcdkeys.com,
     * "gog-com" is gog.com), so it cannot be transformed mechanically without
     * inventing hostnames that do not resolve. This list is therefore explicit
     * and deliberately limited to storefronts whose domain is known; extend it
     * through the filter rather than by guessing.
     *
     * @return array<string,string>
     */
    public static function merchantDomainMap()
    {
        return array(
            'cjs-cdkeys' => 'cjs-cdkeys.com',
            'difmark' => 'difmark.com',
            'driffle' => 'driffle.com',
            'electronicfirst' => 'electronicfirst.com',
            'eneba' => 'eneba.com',
            'fanatical' => 'fanatical.com',
            'g2a' => 'g2a.com',
            'gamebillet' => 'gamebillet.com',
            'gamersgate' => 'gamersgate.com',
            'gameseal' => 'gameseal.com',
            'gamesplanet' => 'gamesplanet.com',
            'gamingdragons' => 'gamingdragons.com',
            'gamivo' => 'gamivo.com',
            'gog-com' => 'gog.com',
            'hrk' => 'hrkgame.com',
            'instant-gaming' => 'instant-gaming.com',
            'k4g' => 'k4g.com',
            'kinguin' => 'kinguin.net',
            'play-asia' => 'play-asia.com',
            'royal-cd-keys' => 'royalcdkeys.com',
            'wingamestore' => 'wingamestore.com',
            'yuplay' => 'yuplay.com',
        );
    }

    /**
     * An unmapped merchant returns an empty string, and the base class then
     * derives the domain from the image URL, which yields "allkeyshop.com".
     * That is a fallback, not an answer - extend the map via
     * cegg_allkeyshop_merchant2domain to name the real storefront.
     */
    private static function getMerchantDomain(array $merchant)
    {
        $slug = isset($merchant['slug']) ? strtolower(trim((string) $merchant['slug'])) : '';
        $name = isset($merchant['name']) ? (string) $merchant['name'] : '';

        $map = (array) \apply_filters(
            'cegg_allkeyshop_merchant2domain',
            self::merchantDomainMap(),
            $slug,
            $name
        );

        if ($slug !== '' && !empty($map[$slug]))
            return (string) $map[$slug];

        return '';
    }

    private static function getCoupon(array $offer)
    {
        if (!empty($offer['coupon']['code']))
            return $offer['coupon'];

        if (!empty($offer['voucher']['code']))
            return $offer['voucher'];

        return null;
    }

    /**
     * AllKeyShop expresses discount as a fraction, so 0.15 is 15% off.
     */
    private static function couponBadge($coupon)
    {
        if (!$coupon || empty($coupon['code']))
            return '';

        $code = (string) $coupon['code'];

        if (empty($coupon['discount']))
            return TextHelper::truncate(sprintf(__('Coupon %s', 'content-egg'), $code));

        $percent = round((float) $coupon['discount'] * 100);

        return TextHelper::truncate(sprintf(__('Coupon %1$s (-%2$s%%)', 'content-egg'), $code, $percent));
    }

    private static function getGenres(array $product)
    {
        if (empty($product['tags']) || !is_array($product['tags']))
            return '';

        $genres = array();
        foreach ($product['tags'] as $tag)
        {
            if (empty($tag['name']) || empty($tag['category']['slug']))
                continue;

            if ($tag['category']['slug'] == 'top_level_genres')
                $genres[] = $tag['name'];
        }

        return implode(', ', $genres);
    }

    private static function stockStatus($status)
    {
        if ($status == 'in_stock')
            return ContentProduct::STOCK_STATUS_IN_STOCK;

        if ($status == 'out_of_stock')
            return ContentProduct::STOCK_STATUS_OUT_OF_STOCK;

        return ContentProduct::STOCK_STATUS_UNKNOWN;
    }

    public static function getPriceParamMap()
    {
        return array(
            'min' => 'priceFrom',
            'max' => 'priceTo',
        );
    }

    public static function getSortByNewestParamMap()
    {
        return array(
            'sort_field' => 'release_date',
            'sort_order' => 'desc',
        );
    }

    private function getApiClient()
    {
        if ($this->api_client === null)
            $this->api_client = new \ContentEgg\application\libs\allkeyshop\AllkeyshopApi($this->config('token'));

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
