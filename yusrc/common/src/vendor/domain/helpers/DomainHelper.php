<?php

namespace yubundle\common\vendor\domain\helpers;

use yii\helpers\ArrayHelper;
use yubundle\account\domain\v2\filters\token\DefaultFilter;
use yii2rails\domain\enums\Driver;

class DomainHelper  {

    public static function config($config = []) {
        $baseConfig = [
            'class' => \yii2tool\vendor\domain\Domain::class,
            'defaultDriver' => Driver::FILE,
            'repositories' => [
                'info',
                'package',
                'generator',
                'git',
                'test',
                'pretty',
            ],
            'services' => [
                'info' => [
                    'ignore' => [
                        //'yii2module/yii2-test',
                    ],
                ],
                'package' => [
                    'aliases' => [
                        '@root',
                        //'@vendor/yii2rails/yii2-extension/yusrc/common',
                        //'@vendor/yii2bundle/yii2-application-template',
                    ],
                ],
                'git',
                'test' => [
                    'aliases' => [
                        'api',
                        'backend',
                        'common',
                        'frontend',
                        'domain',
                    ],
                ],
                'generator' => [
                    'author' => 'Yamshikov Vitaliy',
                    'email' => 'theyamshikov@yandex.ru',
                ],
                'pretty',
            ],
        ];
        return ArrayHelper::merge($baseConfig, $config);
    }

}
