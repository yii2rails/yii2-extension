<?php

namespace yii2rails\extension\package\domain\entities;

use yii\helpers\ArrayHelper;
use yii2rails\domain\BaseEntity;

/**
 * Class GroupEntity
 * 
 * @package yii2rails\extension\package\domain\entities
 *
 * @property $name
 * @property $provider_name
 * @property $url
 * @property $authors
 * @property ProviderEntity $provider
 */
class GroupEntity extends BaseEntity {
	
	protected $name;
	protected $provider_name;
	protected $url;
	protected $authors;
    protected $provider;

    public function fieldType() {
        return [
            'provider' => ProviderEntity::class,
        ];
    }
}
