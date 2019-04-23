<?php

namespace yii2rails\extension\telegram\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ActionEntity
 * 
 * @package yii2rails\extension\telegram\entities
 * 
 * @property $id
 * @property $class
 * @property $params
 * @property $status
 */
class ActionEntity extends BaseEntity {

	protected $id;
	protected $class;
	protected $params;
    protected $status;
}
