<?php

namespace ContentEgg\application\modules\LomadeeProducts;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\ExtraData;

/**
 * ExtraDataLomadeeProducts class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 *
 */
class ExtraDataLomadeeProducts extends ExtraData
{
	public $product_id;
	public $product_mongo_id;
	public $organizationId;

	public $createdAt;
	public $updatedAt;
	public $lastUpdate;

	public $seller;
	public $stock_value;
	public $option_id;
}
