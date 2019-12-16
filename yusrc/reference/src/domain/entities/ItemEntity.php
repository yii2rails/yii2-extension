<?php

namespace yubundle\reference\domain\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;
use yii2rails\extension\common\enums\StatusEnum;

/**
 * Class ItemEntity
 * 
 * @package yubundle\reference\domain\entities
 * 
 * @property $id
 * @property $reference_book_id
 * @property $parent_id
 * @property $code
 * @property $value
 * @property $short_value
 * @property $entity
 * @property $props
 * @property $sort
 * @property $status
 * @property $created_at
 * @property $updated_at
 */
class ItemEntity extends BaseEntity {

	protected $id;
	protected $reference_book_id;
	protected $parent_id;
	protected $code;
	protected $value;
	protected $short_value;
	protected $entity;
	protected $props;
    protected $sort = 0;
    protected $status = StatusEnum::ENABLE;
	protected $created_at;
	protected $updated_at;

    public function fieldType()
    {
        return [
            'id' => 'integer',
            'reference_book_id' => 'integer',
            //'parent_id' => 'integer',
            'status' => 'integer',
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
        ];
    }

    public function rules()
    {
        return [
            ['status', 'in', 'range' => StatusEnum::values()],
            [['code', 'value','short_value','entity'], 'required'],
        ];
    }
}
