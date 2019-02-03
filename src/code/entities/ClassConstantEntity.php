<?php

namespace yii2rails\extension\code\entities;

use yii\helpers\Inflector;
use yii2rails\domain\BaseEntity;

/**
 * Class ClassConstantEntity
 *
 * @package yii2rails\extension\code\entities
 *
 * @property string $name
 * @property mixed $value
 */
class ClassConstantEntity extends BaseEntity {
	
	protected $name = null;
	protected $value = null;
	
	public function rules() {
		return [
			[['name','value'], 'required'],
		];
	}
	
	public function getName() {
		$underscore = Inflector::underscore($this->name);
		return strtoupper($underscore);
	}
	
}