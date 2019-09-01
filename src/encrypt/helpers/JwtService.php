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

    private $profileInstances = [];

    public function setProfile(string $profileName, array $definition) {
        $this->profileInstances[$profileName] = ConfigProfileHelper::createInstanse($definition, JwtProfileEntity::class);
    }

    public function getProfile(string $profileName) : JwtProfileEntity {
        if(!isset($this->profileInstances[$profileName])) {
            $this->profileInstances[$profileName] = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        }
        return $this->profileInstances[$profileName];
    }

    public function sign(JwtEntity $jwtEntity, string $profileName) : string {
        $profileEntity = $this->getProfile($profileName);
        $token = JwtHelper::sign($jwtEntity, $profileEntity);
        return $token;
    }

    public function verify(string $token, string $profileName) : JwtEntity {
        $profileEntity = $this->getProfile($profileName);
        $jwtEntity = JwtHelper::decode($token, $profileEntity);
        return $jwtEntity;
    }

    public function decode(string $token) {
        $jwtEntity = JwtEncodeHelper::decode($token);
        return $jwtEntity;
    }

}
