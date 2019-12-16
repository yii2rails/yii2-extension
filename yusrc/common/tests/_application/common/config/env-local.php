<?php

$config = [
    'servers' => [
        'db' => [
            'main' => [
                'driver' => 'sqlite',
                'dbname' => '@common/runtime/sqlite/test.db',
            ],
        ]
    ],
];

$configFile = __DIR__ . '/../../../../../../../vendor/yubundle/yii2-common/src/project/common/config/env-local.php';
return \yii2rails\extension\common\helpers\Helper::includeConfig($configFile, $config);