<?php

namespace ContentEgg\application\modules\Allkeyshop;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\ExtraData;

/**
 * ExtraDataAllkeyshop class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ExtraDataAllkeyshop extends ExtraData
{
    // AllKeyShop product id. The update path depends on this being present.
    public $product_id;
    public $offer_id;
    public $region;
    public $edition;
    public $activation_platform;
    public $product_type;
    public $release_date;
    public $pegi;
    public $publisher;
    public $genres;
    public $offers_count;
    public $coupon_code;
    public $coupon_discount;
    public $deal_score;
    public $official_website;
}
