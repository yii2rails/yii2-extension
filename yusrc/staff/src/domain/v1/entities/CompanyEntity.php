<?php

namespace yubundle\staff\domain\v1\entities;

use domain\mail\v1\entities\DomainEntity;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\behaviors\entity\TimeValueFilter;
use yii2rails\domain\values\TimeValue;
use yii2rails\extension\common\enums\StatusEnum;

/**
 * Class CompanyEntity
 * 
 * @package yubundle\staff\domain\v1\entities
 * 
 * @property $id
 * @property $code
 * @property $name
 * @property $status
 * @property $created_at
 * @property $updated_at
 * @property DomainEntity[] $domains
 */
class CompanyEntity extends BaseEntity {

	protected $id;
	protected $code;
	protected $name;
	protected $status;
	protected $created_at;
	protected $updated_at;
	protected $domains;

    public function behaviors() {
        return [
            [
                'class' => TimeValueFilter::class,
            ],
        ];
    }

    public function fieldType()
    {
        return [
            'id' => 'integer',

            'status' => 'integer',
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
        ];
    }

    public function rules()
    {
        return [
            ['status', 'in', 'range' => StatusEnum::values()],
        ];
    }
}
