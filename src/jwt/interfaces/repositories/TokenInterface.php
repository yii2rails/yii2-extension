<?php

namespace yii2rails\extension\jwt\interfaces\repositories;

use yii2rails\domain\interfaces\repositories\RepositoryInterface;
use yii2rails\extension\jwt\entities\ProfileEntity;
use yii2rails\extension\jwt\entities\TokenEntity;

/**
 * Interface TokenInterface
 * 
 * @package yii2rails\extension\jwt\interfaces\repositories
 * 
 * @property-read \yii2rails\extension\jwt\Domain $domain
 */
interface TokenInterface extends RepositoryInterface {

    public function sign(TokenEntity $tokenEntity, ProfileEntity $profileEntity, $keyId = null, $head = null);
    public function encode(TokenEntity $tokenEntity, ProfileEntity $profileEntity);
    public function decode($token, ProfileEntity $profileEntity);
    public function decodeRaw($token, ProfileEntity $profileEntity);

}
