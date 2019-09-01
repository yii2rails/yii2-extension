<?php

namespace yii2rails\extension\encrypt\strategies\func;

use yii2bundle\account\domain\v3\helpers\LoginTypeHelper;
use yii2rails\extension\encrypt\dto\TokenDto;
use yii2rails\extension\encrypt\enums\EncryptFunctionEnum;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\encrypt\strategies\func\handlers\EmailStrategy;
use yii2rails\extension\encrypt\strategies\func\handlers\HmacStrategy;
use yii2rails\extension\encrypt\strategies\func\handlers\LoginStrategy;
use yii2rails\extension\encrypt\strategies\func\handlers\OpenSslStrategy;
use yii2rails\extension\encrypt\strategies\func\handlers\PhoneStrategy;
use yii2rails\extension\encrypt\strategies\func\handlers\TokenStrategy;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\dto\WithDto;
use yii2rails\domain\entities\relation\RelationEntity;
use yii2rails\domain\enums\RelationEnum;
use yii2rails\extension\scenario\base\BaseStrategyContextHandlers;
use yii2rails\extension\encrypt\strategies\func\handlers\One;
use yii2rails\extension\encrypt\strategies\func\handlers\Many;
use yii2rails\extension\encrypt\strategies\func\handlers\ManyToMany;
use yii2rails\extension\encrypt\strategies\func\handlers\HandlerInterface;

/**
 * @property-read HandlerInterface $strategyInstance
 */
class FuncContext extends BaseStrategyContextHandlers {

    public function getStrategyDefinitions() {
        return [
            EncryptFunctionEnum::OPENSSL => OpenSslStrategy::class,
            EncryptFunctionEnum::HASH_HMAC => HmacStrategy::class,
        ];
    }

    public function sign($msg, $algorithm, $key)
    {
        return $this->strategyInstance->sign($msg, $algorithm, $key);
    }

    public function verify($msg, $algorithm, $key, $signature)
    {
        return $this->strategyInstance->verify($msg, $algorithm, $key, $signature);
    }

}