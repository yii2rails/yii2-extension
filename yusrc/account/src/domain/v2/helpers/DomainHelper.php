<?php

namespace yubundle\account\domain\v2\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\extension\common\enums\StatusEnum;
use yubundle\account\domain\v2\filters\token\DefaultFilter;
use yii2rails\domain\enums\Driver;

class DomainHelper  {

    public static function config($config = []) {
        $baseConfig = [
            'class' => 'yubundle\account\domain\v2\Domain',
            'services' => [
                'auth' => [
                    'tokenAuthMethods' => [
                        'jwt' => DefaultFilter::class,
                    ],
                ],
                'login' => [
                    'forbiddenStatusList' => [StatusEnum::DISABLE],
                ],
            ],
        ];
        return ArrayHelper::merge($baseConfig, $config);
    }

}
