<?php

namespace yubundle\user\domain\v1\entities;

use yii2rails\extension\common\enums\StatusEnum;
use yubundle\user\domain\v1\entities\PersonEntity;
use yii\helpers\ArrayHelper;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;
use yubundle\account\domain\v2\entities\SecurityEntity;
use yubundle\account\domain\v2\interfaces\entities\LoginEntityInterface;

/**
 * Class ClientEntity
 *
 * @package yubundle\user\domain\v1\entities
 *
 * @property integer          $id
 * @property $person_id
 * @property $balance
 * @property $status
 * @property $created_at
 * @property $updated_at
 */
class ClientEntity extends BaseEntity {

    protected $id;
    protected $person_id;
    protected $balance = 0;
    protected $status = StatusEnum::ENABLE;
    protected $created_at;
    protected $updated_at;

    public function init() {
        parent::init();
        $this->created_at = new TimeValue;
        $this->created_at->setNow();
        $this->updated_at = new TimeValue;
        $this->updated_at->setNow();
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

