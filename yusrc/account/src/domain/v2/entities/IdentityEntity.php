<?php

namespace yubundle\account\domain\v2\entities;

use yii2rails\domain\BaseEntity;

/*
 * Class IdentityEntity
 * 
 * @package yubundle\account\domain\v2\entities
 * 
 * @property $id
 * @property $login
 * @property $roles
 * @property $token
 * @property $status
 * @property $created_at
 */
class IdentityEntity extends LoginEntity {

	/*protected $id;
	protected $login;
	protected $roles;
	protected $token;
	protected $status;
	protected $created_at;*/

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['security']);
        return $fields;
    }
}
