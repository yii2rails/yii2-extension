<?php

namespace yubundle\reference\domain\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;

/**
 * Class EnumEntity
 *
 * @package yubundle\reference\domain\entities
 *
 * @property $id
 * @property $reference_item_id
 * @property $language_code
 * @property $value
 * @property $short_value
 * @property $created_at
 * @property $updated_at
 */
class LocalizationEntity extends BaseEntity
{

    protected $id;
    protected $reference_item_id;
    protected $language_code;
    protected $value;
    protected $short_value;
    protected $created_at;
    protected $updated_at;

    public function fieldType()
    {
        return [
            'id' => 'integer',
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
        ];
    }
}
