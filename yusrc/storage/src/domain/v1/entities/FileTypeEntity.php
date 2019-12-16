<?php

namespace yubundle\storage\domain\v1\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;

/**
 * Class FileTypeEntity
 * 
 * @package yubundle\storage\domain\v1\entities
 * 
 * @property $id
 * @property $code
 * @property $name
 * @property $icon_file
 * @property $handler_name
 * @property $status
 * @property $created_at
 * @property $updated_at
 */
class FileTypeEntity extends BaseEntity {

	protected $id;
    protected $code;
	protected $name;
	protected $icon_file;
	protected $handler_name;
	protected $status;
	protected $created_at;
	protected $updated_at;

    public function fieldType()
    {
        return [
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
        ];
    }
}
