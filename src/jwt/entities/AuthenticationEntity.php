<?php

namespace yii2rails\extension\jwt\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class AuthenticationEntity
 * 
 * @package yii2rails\extension\jwt\entities
 *
 * @property $login string
 * @property $password string
 * @property $type string
 */
class AuthenticationEntity extends BaseEntity {

    protected $login;
    protected $password;
    protected $type;

    public function rules() {
        return [
            [['login', 'password'], 'trim'],
            [['login', 'password'], 'required'],
        ];
    }

}
