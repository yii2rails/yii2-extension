<?php

namespace yii2rails\extension\code\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ClassMethodParameterEntity
 *
 * @package yii2rails\extension\code\entities
 *
 * @property string $type
 * @property string $name
 * @property mixed $value
 */
class ClassMethodParameterEntity extends BaseEntity {
	
	protected $name;
	protected $type;
	protected $value;
	
	public function rules() {
		return [
			[['name'], 'required'],
		];
	}
}