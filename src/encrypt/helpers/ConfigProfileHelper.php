<?php

namespace yii2rails\extension\encrypt\helpers;

use yii\base\InvalidConfigException;
use yii2rails\extension\encrypt\entities\ConfigEntity;
use yii2rails\extension\encrypt\entities\ProfileEntity;
use yii2rails\extension\enum\base\BaseEnum;
use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\encrypt\entities\KeyEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\RsaBitsEnum;

class ConfigProfileHelper {

    public static function load(string $profile, $profileEntityClass = ProfileEntity::class) : ProfileEntity
    {
        $config = EnvService::get('encrypt.profiles.' . $profile);
        $profileEntity = new $profileEntityClass($config);
        if($profileEntity->key == null) {
            throw new InvalidConfigException('Empty encryption key in profile!');
        }
        return $profileEntity;
    }

}
