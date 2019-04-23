<?php

namespace yii2rails\extension\telegram\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class UpdateEntity
 * 
 * @package yii2rails\extension\telegram\entities
 * 
 * @property $id
 * @property $message
 */
class UpdateEntity extends BaseEntity {

	protected $id;
	protected $message;

}
