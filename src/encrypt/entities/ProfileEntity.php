<?php

namespace yii2rails\extension\encrypt\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;

/**
 * Class ConfigEntity
 * @package yii2rails\extension\encrypt\entities
 *
 * @property KeyEntity $key
 * @property string $algorithm
 */
class ProfileEntity extends BaseEntity {

	protected $key;
	protected $algorithm = EncryptAlgorithmEnum::SHA256;

	public function fieldType()
    {
        return [
            'key' => KeyEntity::class,
        ];
    }
}
