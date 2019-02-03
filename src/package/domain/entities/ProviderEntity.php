<?php

namespace yii2rails\extension\package\domain\entities;

use yii\helpers\ArrayHelper;
use yii2rails\domain\BaseEntity;

/**
 * Class ProviderEntity
 * 
 * @package yii2rails\extension\package\domain\entities
 *
 * @property $name
 * @property $host
 * @property $url_templates
 */
class ProviderEntity extends BaseEntity {
	
	protected $name;
	protected $host;
    protected $url_templates;

}
