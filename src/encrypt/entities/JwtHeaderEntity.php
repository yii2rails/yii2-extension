<?php

namespace yii2rails\extension\encrypt\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\enum\enums\TimeEnum;

/**
 * Class JwtHeaderEntity
 * @package yii2rails\extension\encrypt\entities
 *
 * @property $typ string
 * @property $alg string
 * @property $kid string
 */
class JwtHeaderEntity extends BaseEntity {

    protected $typ = 'JWT';
    protected $alg = 'HS256';
    protected $kid;

}
