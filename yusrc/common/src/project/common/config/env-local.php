<?php

return [
	'servers' => [
		'db' => [
            'main' => [
                'dbname' => '@common/runtime/sqlite/main.db',
            ],
		],
	],
	'mode' => [
		'env' => 'dev',
		'debug' => true,
	],
	'jwt' => [
		'profiles' => [
			'default' => [
				'key' => 'IeWUAWlY55VVPOEOtbp1PH1NXOotU0Wh',
				'issuer_url' => 'http://api.yumail.project/v1/auth',
				'life_time' => 1200,
				'allowed_algs' => [
					'HS256',
					'SHA512',
					'HS384',
				],
				'default_alg' => 'HS256',
				'audience' => [
					'http://api.yumail.project',
				],
			],
			'auth' => [
				'key' => 'W4PpvVwI82Rfl9fl2R9XeRqBI0VFBHP3',
				'issuer_url' => 'http://api.yumail.project/v1/auth',
				'life_time' => 1200,
				'allowed_algs' => [
					'HS256',
					'SHA512',
					'HS384',
				],
				'default_alg' => 'HS256',
				'audience' => [
					'http://api.yumail.project',
				],
			],
		],
	],
	'domain' => [
		'driver' => [
			'primary' => 'ar',
			'slave' => 'ar',
		],
	],
];
