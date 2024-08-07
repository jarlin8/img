<?php

namespace ContentEgg\application\modules\Aliexpress;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\ExtraData;

/**
 * ExtraDataAliexpress class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2023 keywordrush.com
 */
class ExtraDataAliexpress extends ExtraData
{

	public $lotNum;
	public $packageType;
	public $_30daysCommission;
	public $commissionRate;
	public $validTime;
	public $volume;
	public $evaluateScore;
	public $commission;
}
