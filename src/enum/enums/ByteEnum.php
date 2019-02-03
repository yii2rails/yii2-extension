<?php

namespace yii2rails\extension\enum\enums;

use yii2rails\extension\enum\base\BaseEnum;

class ByteEnum extends BaseEnum {
	
	const BIT_PER_BYTE = 8;
	const STEP = 1024;
	
	const KB = self::STEP;
	const MB = self::KB * self::STEP;
	const GB = self::MB * self::STEP;
	const TB = self::GB * self::STEP;
	const PB = self::TB * self::STEP;
	const EB = self::PB * self::STEP;
	const ZB = self::EB * self::STEP;
	const YB = self::ZB * self::STEP;
	
}
