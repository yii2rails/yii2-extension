<?php

namespace yubundle\storage\domain\v1\entities;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\behaviors\entity\TimeValueFilter;
use yubundle\user\domain\v1\entities\PersonEntity;

/**
 * Class FileUrlEntity
 * 
 * @package yubundle\storage\domain\v1\entities
 * 
 * @property $constant
 * @property $source
 * @property $download
 */
class FileUrlEntity extends BaseEntity {

	protected $constant;
	protected $source;
	protected $download;

}
