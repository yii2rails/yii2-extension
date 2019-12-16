<?php

namespace yubundle\account\domain\v2\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class TestEntity
 *
 * @package yubundle\account\domain\v2\entities
 *
 * @property $id integer
 * @property $login string
 * @property $username string
 * @property $email string
 * @property $role string
 * @property $status integer
 */
class TestEntity extends BaseEntity {

	protected $id;
	protected $login;
	protected $username;
	protected $email;
	protected $role;
	protected $status;

}
