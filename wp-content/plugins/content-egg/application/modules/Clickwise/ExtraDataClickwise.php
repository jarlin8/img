<?php

namespace ContentEgg\application\modules\Clickwise;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\ExtraData;

/**
 * ExtraDataClickwise class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ExtraDataClickwise extends ExtraData
{
    public $country;
    public $merchant_id;
    public $group_id;
    public $availability;
    public $size;
    public $color;
    public $sizes;
    public $updated_at;
}
