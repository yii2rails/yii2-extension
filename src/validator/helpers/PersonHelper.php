<?php

namespace yii2rails\extension\validator\helpers;

use yii2rails\extension\common\helpers\StringHelper;
use yii2woop\operation\domain\v2\entities\IinEntity;

class PersonHelper {
	
	public static function getFio(IinEntity $iinEntity, $asNormalize = false) {
		$fio =
			self::normalizeName($iinEntity->family, $asNormalize) . SPC .
			self::normalizeName($iinEntity->name, $asNormalize) . SPC .
			self::normalizeName($iinEntity->patronymic, $asNormalize);
		$fio = StringHelper::removeDoubleSpace($fio);
		return $fio;
	}
	
	private static function normalizeName($name, $asNormalize = false) {
		if(!$asNormalize) {
			return $name;
		}
		$name = mb_strtolower($name);
		$name = mb_ucfirst($name);
		return $name;
	}
	
}
