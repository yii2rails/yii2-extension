<?php

namespace yubundle\common\partner\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class AuthEntity
 * 
 * @package yubundle\common\partner\entities
 * 
 * @property $id
 * @property $login
 * @property $token
 * @property $status
 * @property $created_at
 * @property $updated_at
 */
class IdentityEntity extends BaseEntity {

	protected $id;
	protected $login;
    protected $token;
	protected $status;
	protected $created_at;
	protected $updated_at;

}
