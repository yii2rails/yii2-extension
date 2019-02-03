<?php

namespace yii2rails\extension\code\helpers\generator;

use yii\helpers\ArrayHelper;

class EnumGeneratorHelper {
	
	private static $defaultConfig = [
		'use' => ['yii2rails\extension\enum\base\BaseEnum'],
		'afterClassName' => 'extends BaseEnum',
	];
	
	public static function generate($config) {
		$config = ArrayHelper::merge($config, self::$defaultConfig);
		ClassGeneratorHelper::generate($config);
	}
	
}
