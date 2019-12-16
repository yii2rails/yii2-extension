<?php

namespace yubundle\staff\domain\v1\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class WorkerSchema
 *
 * @package yubundle\staff\domain\v1\repositories\schema
 *
 */
class WorkerSchema extends BaseSchema
{

    public function relations()
    {
        return [
            'person' => [
                'type' => RelationEnum::ONE,
                'field' => 'person_id',
                'foreign' => [
                    'id' => 'user.person',
                    'field' => 'id',
                ],
            ],
            'division' => [
                'type' => RelationEnum::ONE,
                'field' => 'division_id',
                'foreign' => [
                    'id' => 'staff.division',
                    'field' => 'id',
                ],
            ],
            'post' => [
                'type' => RelationEnum::ONE,
                'field' => 'post_id',
                'foreign' => [
                    'id' => 'reference.item',
                    'field' => 'id',
                ],
            ],
            'user' => [
                'type' => RelationEnum::ONE,
                'field' => 'person_id',
                'foreign' => [
                    'id' => 'account.login',
                    'field' => 'person_id',
                ],
            ],
        ];
    }


}
