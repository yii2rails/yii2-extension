<?php

namespace yubundle\storage\domain\v1\entities;

use domain\mail\v1\entities\AttachmentEntity;
use domain\mail\v1\entities\BoxEntity;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;
use yubundle\user\domain\v1\entities\PersonEntity;

/**
 * Class PolicyEntity
 * 
 * @package yubundle\storage\domain\v1\entities
 * 
 * @property $id
 * @property $service_id
 * @property $role
 * @property $file_size
 * @property $space_size
 * @property $allow_extensions
 * @property $allow_types
 */
class PolicyEntity extends BaseEntity {

	protected $id;
	protected $service_id;
	protected $role;
	protected $file_size = 0;
	protected $space_size = 0;
	protected $allow_extensions = [];
	protected $allow_types = [];
	protected $created_at;
	protected $updated_at;

    public function fieldType()
    {
        return [
            'id' => 'integer',
            'service_id' => 'integer',
            'role' => 'string',
            
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
        ];
    }

}
