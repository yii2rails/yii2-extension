<?php

namespace yii2rails\extension\git\domain\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class TagEntity
 * 
 * @package yii2rails\extension\git\domain\entities
 * 
 * @property $name
 * @property $hash
 */
class TagEntity extends BaseEntity {

	protected $name;
	protected $hash;

}
