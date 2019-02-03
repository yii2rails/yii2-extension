<?php

namespace yii2rails\extension\git\domain\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class RemoteEntity
 * 
 * @package yii2rails\extension\git\domain\entities
 * 
 * @property $name
 * @property $url
 * @property $fetch
 * @property $pushurl
 */
class RemoteEntity extends BaseEntity {

	protected $name;
	protected $url;
	protected $fetch;
	protected $pushurl;

}
