<?php

namespace yii2lab\extension\package\domain\entities;

use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;

/**
 * Class ProviderEntity
 * 
 * @package yii2lab\extension\package\domain\entities
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
