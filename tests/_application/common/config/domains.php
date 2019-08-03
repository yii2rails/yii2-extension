<?php

use yii\helpers\ArrayHelper;
use yii2tool\test\helpers\TestHelper;

$config = [
    'rbac' => [
        'class' => 'yii2bundle\rbac\domain\Domain',
        'repositories' => [
            'rule' => [
                'ruleFile' => '@vendor/yii2tool/yii2-test/src/base/_application/common/data/rbac/rules.php',
            ],
            'item' => [
                'itemFile' => '@vendor/yii2tool/yii2-test/src/base/_application/common/data/rbac/items.php',
            ],
        ],
    ],
    'jwt' => 'yii2rails\extension\jwt\Domain',
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
