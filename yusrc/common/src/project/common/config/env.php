<?php

use yii2rails\app\domain\commands\ApiVersion;
use yii2rails\app\domain\commands\RunBootstrap;
use yii2rails\app\domain\filters\config\LoadConfig;
use yii2rails\app\domain\filters\config\LoadModuleConfig;
use yii2rails\app\domain\filters\config\LoadRouteConfig;
use yii2rails\domain\filters\LoadDomainConfig;
use yii2rails\app\domain\enums\YiiEnvEnum;

return [
	'api' => [
		'defaultVersion' => 1,
	],
    'aliases' => [],
    'cache' => [
        'enable' => false, //YII_ENV == YiiEnvEnum::PROD,
    ],
    /*'dev' => [
        'registerSaveDeclaredClasses' => true,
    ],*/
    'client' => [
        'defaultTimeZone' => 'Asia/Almaty',
    ],
	'servers' => [
        /*'storage' => [
            'apiHost' => 'http://api.storage.srv/',
            'resourceHost' => 'http://storage.srv/',
            'driver' => 'core',
        ],
        'core' => [
            'host' => 'http://api.core.srv/',
        ],*/
        'socket' => [
            'tcp' => [
                'host' => 'tcp://127.0.0.1:1234',
            ],
            'ws' => [
                'host' => 'websocket://0.0.0.0:8000',
                'context' => [
                    /*'ssl' => array(
                        'local_cert'  => '/your/path/of/server.pem',
                        'local_pk'    => '/your/path/of/server.key',
                        'verify_peer' => false,
                    )*/
                ],
            ],
        ],
		'static' => [
			'profiles' => [
				'storage' => [
					'path' => 'storage',
				],
			],
		],
		'db' => [
			'main' => [
				'driver' => 'sqlite',
				'dbname' => '@common/runtime/sqlite/main.db',
				'map' => [
					'user_assignment' => 'user_assignment',
                    'user_confirm' => 'user_confirm',
                    'user_person' => 'main.persons',
					'users' => 'main.users',
                    'user_login' => 'main.users',
					'portal_client' => 'portal.clients',

					'reference_book' => 'common.reference_books',
					'reference_item' => 'common.reference_items',
                    'reference_item_localization' => 'common.localizations',

					'rest_collection' => 'rest',

                    'migration' => 'news.migration',
					'language' => 'news.language',

                    'model_entity' => 'model.entity',
                    'model_book' => 'model.book',
                    'model_field' => 'model.field',
                    'model_rule' => 'model.rule',
                    'model_enum' => 'model.enum',

                    'storage_file' => 'storage.file',
                    'storage_file_extension' => 'storage.file_extension',
                    'storage_file_type' => 'storage.file_type',
                    'storage_image' => 'storage.image',
                    'storage_service' => 'storage.service',
                    'storage_service_thumb' => 'storage.service_thumb',
                    'storage_policy' => 'storage.service_policy',

                    'geo_country' => 'geo.country',
                    'geo_region' => 'geo.region',
                    'geo_city' => 'geo.city',
                    'geo_currency' => 'geo.currency',
                    'geo_currency_value' => 'geo.currency_value',

                    'notify_sms_queue' => 'notify.sms_queue',

                    'staff_division' => 'staff.divisions',
                    'staff_worker' => 'staff.workers',
				],
			],
			'test' => [
				'driver' => 'sqlite',
				'dbname' => '@common/runtime/sqlite/test.db',
			],
			'filedb' => [
				'path' => '@common/data',
			],
		],
	],
	'app' => [
		'commands' => [
			[
				'class' => RunBootstrap::class,
				'app' => COMMON,
			],
			[
				'class' => RunBootstrap::class,
				'app' => APP,
			],
			[
				'class' => ApiVersion::class,
				'isEnabled' => APP == API,
			],
            [
                'class' => 'yii2rails\domain\filters\DefineDomainLocator',
                'filters' => [
                    [
                        'class' => LoadDomainConfig::class,
                        'app' => COMMON,
                        'name' => 'domains',
                        'withLocal' => true,
                    ],
                ],
            ],
            /*[
                'class' => 'yii2tool\offline\domain\filters\IsOffline',
                'exclude' => [
                    CONSOLE,
                    BACKEND,
                ],
            ],*/
		],
	],
	'config' => [
		'filters' => [
			[
				'class' => LoadConfig::class,
				'app' => COMMON,
				'name' => 'main',
				'withLocal' => true,
			],
			[
				'class' => LoadConfig::class,
				'app' => APP,
				'name' => 'main',
				'withLocal' => true,
			],
			
			[
				'class' => LoadModuleConfig::class,
				'app' => COMMON,
				'name' => 'modules',
				'withLocal' => true,
			],
			[
				'class' => LoadModuleConfig::class,
				'app' => APP,
				'name' => 'modules',
				'withLocal' => true,
			],
			
			[
				'class' => LoadRouteConfig::class,
				'app' => APP,
				'name' => 'routes',
				'withLocal' => true,
			],
			
			[
				'class' => LoadConfig::class,
				'app' => COMMON,
				'name' => 'params',
				'withLocal' => true,
				'assignTo' => 'params',
			],
			[
				'class' => LoadConfig::class,
				'app' => APP,
				'name' => 'params',
				'withLocal' => true,
				'assignTo' => 'params',
			],
			
			[
				'class' => LoadConfig::class,
				'app' => COMMON,
				'name' => 'install',
			],
			[
				'class' => LoadConfig::class,
				'app' => APP,
				'name' => 'install',
			],
			
			[
				'class' => LoadConfig::class,
				'app' => COMMON,
				'name' => 'test',
				'withLocal' => true,
				'isEnabled' => YII_ENV == YiiEnvEnum::TEST,
			],
			[
				'class' => LoadConfig::class,
				'app' => APP,
				'name' => 'test',
				'withLocal' => true,
				'isEnabled' => YII_ENV == YiiEnvEnum::TEST,
			],
			
			'migration' => [
				'class' => 'yii2lab\db\domain\filters\migration\SetPath',
				'path' => [
					'@vendor/yii2bundle/yii2-rbac/src/domain/migrations',
					'@vendor/yii2bundle/yii2-notify/src/domain/migrations',
                    '@vendor/yii2tool/yii2-restclient/src/domain/migrations',
					'@vendor/yii2bundle/yii2-lang/src/domain/migrations',
                    '@vendor/yii2bundle/yii2-notify/src/domain/migrations',
					//'@vendor/yii2bundle/yii2-geo/src/domain/migrations',
                    '@vendor/yii2rails/yii2-extension/yusrc/account/src/domain/v2/migrations',
                    '@vendor/yii2rails/yii2-extension/yusrc/reference/src/domain/migrations',
                    '@vendor/yii2rails/yii2-extension/yusrc/user/src/domain/v1/migrations',
                    '@vendor/yii2rails/yii2-extension/yusrc/storage/src/domain/v1/migrations',
                    '@vendor/yii2rails/yii2-extension/yusrc/staff/src/domain/v1/migrations',
                    '@vendor/yiisoft/yii2/log/migrations'
				],
				'scan' => [
					'@domain',
				],
			],
			
			'yii2rails\app\domain\filters\config\StandardConfigMutations',
			'yii2rails\app\domain\filters\config\DebugModule',
		],
	],
];