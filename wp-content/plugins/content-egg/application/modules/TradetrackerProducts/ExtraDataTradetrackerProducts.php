<?php

namespace ContentEgg\application\modules\TradetrackerProducts;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\ExtraData;

/**
 * ExtraDataTradetrackerProducts class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2023 keywordrush.com
 */
class ExtraDataTradetrackerProducts extends ExtraData
{

	public $deliveryTime;
	public $deliveryCosts;
	public $fromPrice;
	public $stock;
	public $categoryPath;
	public $subcategories;
}
