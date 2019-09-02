<?php

namespace yii2rails\extension\encrypt\libs;

use yii2rails\extension\common\traits\classAttribute\MagicSetTrait;
use yii2rails\extension\encrypt\entities\JwtEntity;
use yii2rails\extension\encrypt\helpers\JwtEncodeHelper;
use yii2rails\extension\encrypt\helpers\JwtHelper;

class JwtService {

    use MagicSetTrait;

    /** @var ProfileContainer */
    private $profileContainer;

    public function sign(JwtEntity $jwtEntity, string $profileName) : string {
        $profileEntity = $this->profileContainer->get($profileName);
        $token = JwtHelper::sign($jwtEntity, $profileEntity);
        return $token;
    }

    public function verify(string $token, string $profileName) : JwtEntity {
        $profileEntity = $this->profileContainer->get($profileName);
        $jwtEntity = JwtHelper::decode($token, $profileEntity);
        return $jwtEntity;
    }

    public function decode(string $token) {
        $jwtEntity = JwtEncodeHelper::decode($token);
        return $jwtEntity;
    }

    public function setProfiles($profiles)
    {
        if(is_array($profiles)) {
            $this->profileContainer = new ProfileContainer($profiles);
        } else {
            $this->profileContainer = $profiles;
        }
    }

    public function __construct($profiles = [])
    {
        $this->setProfiles($profiles);
    }

}
