<?php

namespace yii2rails\extension\code\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ClassUseEntity
 *
 * @package yii2rails\extension\code\entities
 *
 * @property string $name
 * @property string $as
 */
class ClassUseEntity extends BaseEntity {
	
	protected $name = null;
	protected $as = null;
	
	public function rules() {
		return [
			[['name'], 'required'],
		];
	}
}