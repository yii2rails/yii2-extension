<?php

namespace yubundle\storage\domain\v1\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;

/**
 * Class FileExtensionEntity
 * 
 * @package yubundle\storage\domain\v1\entities
 * 
 * @property $id
 * @property $code
 * @property $type_id
 * @property $mime
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property FileTypeEntity $type
 */
class FileExtensionEntity extends BaseEntity {

	protected $id;
	protected $code;
	protected $type_id;
	protected $mime;
	protected $status;
	protected $created_at;
	protected $updated_at;

    protected $type;

    public function fieldType()
    {
        return [
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,

            'type' => FileTypeEntity::class,
        ];
    }
}
