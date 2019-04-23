<?php

namespace yii2rails\extension\telegram\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ChatEntity
 * 
 * @package yii2rails\extension\telegram\entities
 * 
 * @property $id
 * @property $first_name
 * @property $username
 * @property $type
 */
class ChatEntity extends BaseEntity {

	protected $id;
	protected $first_name;
	protected $username;
	protected $type;

}
