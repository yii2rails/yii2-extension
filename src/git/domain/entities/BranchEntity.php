<?php

namespace yii2rails\extension\git\domain\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class BranchEntity
 * 
 * @package yii2rails\extension\git\domain\entities
 * 
 * @property $name
 * @property $remote
 * @property $merge
 */
class BranchEntity extends BaseEntity {

	protected $name;
	protected $remote;
	protected $merge;

}
