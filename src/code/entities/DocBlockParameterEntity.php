<?php

namespace yii2rails\extension\code\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class DocBlockParameterEntity
 *
 * @package yii2rails\extension\code\entities
 *
 * @property string $name
 * @property string $type
 * @property mixed $value
 */
class DocBlockParameterEntity extends BaseEntity {
	
	const NAME_PROPERTY = 'property';
	const NAME_PROPERTY_READ = 'property-read';
	const NAME_DEPRECATED = 'deprecated';
	
	protected $name;
	protected $type;
	protected $value;
	
	public function rules() {
		$names = $this->getConstantEnum('name');
		return [
			[['name'], 'required'],
			[['name'], 'in', 'range' => $names],
		];
	}
}