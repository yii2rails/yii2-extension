<?php

namespace yubundle\account\domain\v2\interfaces\repositories;

use yubundle\account\domain\v2\entities\JwtEntity;
use yubundle\account\domain\v2\entities\JwtProfileEntity;

/**
 * Interface JwtInterface
 * 
 * @package yubundle\account\domain\v2\interfaces\repositories
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
interface JwtInterface {

    public function sign(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity);
    public function encode(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity);
    public function decode($token, JwtProfileEntity $profileEntity);

}
