<?php

namespace yii2rails\extension\encrypt\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\Alias;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\encrypt\entities\JwtEntity;
use yii2rails\extension\encrypt\entities\JwtHeaderEntity;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\entities\JwtTokenEntity;
use yii2rails\extension\encrypt\entities\KeyEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\encrypt\enums\RsaBitsEnum;
use yii2rails\extension\enum\base\BaseEnum;
use UnexpectedValueException;
use yii2rails\extension\jwt\entities\ProfileEntity;

class JwtService {

    private static $profileInstances = [];

    public static function setProfile(string $profileName, array $definition) {
        self::$profileInstances[$profileName] = ConfigProfileHelper::createInstanse($definition);
    }

    public static function getProfile(string $profileName) : ProfileEntity {
        if(!isset(self::$profileInstances[$profileName])) {
            self::$profileInstances[$profileName] = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        }
        return self::$profileInstances[$profileName];
    }

    public static function sign(JwtEntity $jwtEntity, string $profileName) : string {
        $profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $token = JwtHelper::sign($jwtEntity, $profileEntity);
        return $token;
    }

    public static function verify(string $token, string $profileName) : JwtEntity {
        $profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $jwtEntity = JwtHelper::decode($token, $profileEntity);
        return $jwtEntity;
    }

    public static function decode(string $token) {
        $jwtEntity = JwtEncodeHelper::decode($token);
        return $jwtEntity;
    }

}
