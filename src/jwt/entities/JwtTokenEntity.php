<?php

namespace yii2rails\extension\jwt\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class JwtTokenEntity
 * 
 * @package yii2rails\extension\jwt\entities
 *
 * @property $header array
 * @property $payload array
 * @property $sig string
 */
class JwtTokenEntity extends BaseEntity {

    protected $header;
    protected $payload;
    protected $sig;

}
