<?php

namespace yii2rails\extension\changelog\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class TypeEntity
 * 
 * @package yii2rails\extension\changelog\entities
 * 
 * @property $name
 * @property $title
 * @property $version
 * @property $sort
 * @property $desctiption
 */
class TypeEntity extends BaseEntity {

	protected $name;
	protected $title;
    protected $version;
	protected $sort;
    protected $desctiption;

}
