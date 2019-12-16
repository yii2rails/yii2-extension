<?php

namespace yubundle\storage\domain\v1\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;

/**
 * Class ServiceEntity
 * 
 * @package yubundle\storage\domain\v1\entities
 * 
 * @property $id
 * @property $name
 * @property $path
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property ServiceThumbEntity[] $thumbs
 */
class ServiceEntity extends BaseEntity {

	protected $id;
	protected $name;
	protected $path;
	protected $status;
	protected $created_at;
	protected $updated_at;

    protected $thumbs;

    public function fieldType()
    {
        return [
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,

            'thumbs' => [
                'type' => ServiceThumbEntity::class,
                'isCollection' => true,
            ],
        ];
    }
}
