<?php

use yii\helpers\ArrayHelper;

$collection = [
    [
        'name' => 'yuwert',
        'host' => 'http://git.yuwert.kz',
        'url_templates' => [
            'new_tag' => '{package}/tags/new',
            'view_commit' => '{package}/commit/{hash}',
        ],
    ],
];

$baseCollection = require_once(__DIR__ . '/../../../../../../../vendor/yii2rails/yii2-extension/src/package/domain/data/package_provider.php');
return ArrayHelper::merge($baseCollection, $collection);