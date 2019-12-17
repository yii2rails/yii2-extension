<?php

use yii\helpers\ArrayHelper;

$collection = [
    [
        'name' => 'yubundle',
        'provider_name' => 'yuwert',
        'authors' => [
            [
                'name' => 'Yamshikov Vitaliy',
                'email' => 'theyamshikov@yandex.ru',
            ],
            [
                'name' => 'Yuwert LLC',
            ],
        ],
    ],
];

$baseCollection = require_once(__DIR__ . '/../../../../../../../../../vendor/yii2rails/yii2-extension/src/package/domain/data/package_group.php');
return ArrayHelper::merge($baseCollection, $collection);