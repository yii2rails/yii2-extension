<?php

namespace yii2rails\extension\changelog\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class WordEntity
 * 
 * @package yii2rails\extension\changelog\entities
 * 
 * @property $name
 * @property $type_name
 * @property TypeEntity $type
 */
class WordEntity extends BaseEntity {

	protected $name;
	protected $type_name;
    protected $type;

}
