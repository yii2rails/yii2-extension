<?php

namespace yii2rails\extension\code\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\helpers\Helper;

/**
 * Class DocBlockEntity
 *
 * @package yii2rails\extension\code\entities
 *
 * @property string $title
 * @property string $description
 * @property DocBlockParameterEntity[] $parameters
 */
class DocBlockEntity extends BaseEntity {
	
	protected $title;
	protected $description;
	protected $parameters = [];
	
	public function setParameters($value) {
		$this->parameters = Helper::forgeEntity($value, DocBlockParameterEntity::class);
	}
}