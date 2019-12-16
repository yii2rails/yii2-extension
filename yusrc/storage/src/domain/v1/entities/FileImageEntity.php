<?php

namespace yubundle\storage\domain\v1\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;

/**
 * Class FileImageEntity
 * 
 * @package yubundle\storage\domain\v1\entities
 * 
 * @property $id
 * @property $file_id
 * @property $width
 * @property $height
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property FileEntity $file
 */
class FileImageEntity extends BaseEntity {

	protected $id;
	protected $file_id;
	protected $width;
	protected $height;
	protected $status;
	protected $created_at;
	protected $updated_at;

    protected $file;

    public function fieldType()
    {
        return [
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,

            'file' => FileEntity::class,
        ];
    }
}
