<?php

namespace yii2rails\extension\encrypt\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\EncryptFunctionEnum;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\enum\enums\TimeEnum;

/**
 * Class JwtProfileEntity
 * @package yii2rails\extension\encrypt\entities
 *
 * @property $name string
 * @property $life_time integer
 * @property $allowed_algs string[]
 * @property $default_alg string
 * @property $hash_alg string
 * @property $func string
 * @property $audience string[]
 * @property $issuer_url string
 */
class JwtProfileEntity extends ProfileEntity {

    protected $name;
    protected $life_time = TimeEnum::SECOND_PER_MINUTE * 20;
    // protected $allowed_algs = ['HS256', 'SHA512', 'HS384', 'RS256'];
    protected $allowed_algs = [
        JwtAlgorithmEnum::HS256,
        JwtAlgorithmEnum::HS512,
        JwtAlgorithmEnum::HS384,
        JwtAlgorithmEnum::RS256,
    ];
    protected $default_alg = JwtAlgorithmEnum::HS256;
    protected $hash_alg = EncryptAlgorithmEnum::SHA256;
    protected $func = EncryptFunctionEnum::HASH_HMAC;
    protected $audience = [];
    protected $issuer_url;

}
