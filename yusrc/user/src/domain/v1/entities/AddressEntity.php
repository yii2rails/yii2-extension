<?php

namespace yubundle\user\domain\v1\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class AddressEntity
 * 
 * @package yubundle\user\domain\v1\entities
 * 
 * @property $login
 * @property $domain
 * @property-read $email
 */
class AddressEntity extends BaseEntity {

	protected $login;
	protected $domain;

	public function getEmail() {
	    return $this->login . '@' . $this->domain;
    }

}
