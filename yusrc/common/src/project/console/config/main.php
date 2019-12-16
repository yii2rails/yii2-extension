<?php

use yii2bundle\lang\domain\enums\LanguageEnum;

return [
	'language' => LanguageEnum::EN, // current Language
	'components' => [
		'user' => [
			'class' => 'yii2bundle\account\domain\v2\web\User',
			'enableSession' => false, // ! important
		],
	],
	'controllerMap' => [
		'migrate' => 'yii2lab\db\console\controllers\MigrateController',
	],
];
