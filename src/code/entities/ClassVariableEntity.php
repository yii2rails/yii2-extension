<?php

namespace yii2rails\extension\code\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\extension\code\enums\AccessEnum;

/**
 * Class ClassVariableEntity
 *
 * @package yii2rails\extension\code\entities
 *
 * @property string $name
 * @property string $access
 * @property boolean $is_static
 * @property mixed $value
 */
class ClassVariableEntity extends BaseEntity {
	
	protected $name;
	protected $access = AccessEnum::PUBLIC;
	protected $is_static = false;
	protected $value;
	
	public function rules() {
		return [
			[['name','access'], 'required'],
			[['access'], 'in', 'range' => AccessEnum::values()],
		];
	}
}