<?php

namespace yii2rails\extension\encrypt\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\extension\encrypt\entities\JwtHeaderEntity;

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

    public function fieldType()
    {
        return [
            'header' => JwtHeaderEntity::class,
        ];
    }
}
