<?php

namespace yubundle\storage\domain\v1\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ServiceThumbEntity
 * 
 * @package yubundle\storage\domain\v1\entities
 * 
 * @property $id
 * @property $service_id
 * @property $width
 * @property $height
 * @property $extension
 * @property $quality
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property ServiceEntity $service
 * @property FileExtensionEntity $ext
 */
class ServiceThumbEntity extends BaseEntity {

	protected $id;
	protected $service_id;
	protected $width;
	protected $height;
	protected $extension;
	protected $quality;
	protected $status;
	protected $created_at;
	protected $updated_at;

    protected $service;
    protected $ext;

    public function fieldType()
    {
        return [
            'service' => ServiceEntity::class,
            'ext' => FileExtensionEntity::class,
        ];
    }
}
