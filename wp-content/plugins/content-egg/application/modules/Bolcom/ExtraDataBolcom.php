<?php

namespace ContentEgg\application\modules\Bolcom;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\ExtraData;

/**
 * ExtraDataBolcom class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2023 keywordrush.com
 *
 */
class ExtraDataBolcom extends ExtraData
{

	public $gpc;
	public $subtitle;
	public $summary;
	public $media = array();
}
