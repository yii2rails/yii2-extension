<?php

use yii\helpers\ArrayHelper;
use yii2bundle\lang\domain\enums\LanguageEnum;
use yii2rails\app\domain\helpers\EnvService;

return [
	'name' => 'Yuwert App',
	'language' => LanguageEnum::RU, // current Language
	'sourceLanguage' => LanguageEnum::SOURCE, // Language development
	'bootstrap' => ['log', 'language', 'queue'],
	'timeZone' => 'UTC',
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
	],
	'components' => [
		'language' => 'yii2bundle\lang\domain\components\Language',
		'user' => [
			'class' => 'yubundle\account\domain\v2\web\User',
		],
		'log' => [
			'targets' => [
				[
					'class' => 'yii\log\DbTarget',
					'levels' => ['error', 'warning'],
					/*'except' => [
						'yii\web\HttpException:*',
						YII_ENV_TEST ? 'yii2bundle\lang\domain\i18n\PhpMessageSource::loadMessages' : null,
					],*/
				],
			],
			'traceLevel' => YII_DEBUG ? 3 : 0,
		],
		'authManager' => 'yii2bundle\rbac\domain\rbac\PhpManager',
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
			'cachePath' => '@common/runtime/cache',
		],
		'i18n' => [
			'class' => 'yii2bundle\lang\domain\i18n\I18N',
			'aliases' => [
				'*' => '@common/messages',
			],
		],
		'db' => 'yii2lab\db\domain\db\Connection',
		'queue' => [
			'class' => 'yii2rails\extension\queue\drivers\file\Queue',
			'path' => '@common/runtime/queue',
			'autoRun' => !YII_ENV_PROD,
		],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@yii2lab/notify/domain/mail',
            'transport' => EnvService::getServer('mail', []),
        ],
    ],
];
