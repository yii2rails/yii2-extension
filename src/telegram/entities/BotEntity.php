<?php

namespace yii2rails\extension\telegram\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class BotEntity
 * 
 * @package yii2rails\extension\telegram\entities
 * 
 * @property $id
 * @property $username
 * @property $token
 */
class BotEntity extends BaseEntity {

	protected $id;
	protected $username;
	protected $token;

}
