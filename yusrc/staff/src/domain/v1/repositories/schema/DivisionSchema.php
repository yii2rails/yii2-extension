<?php

namespace yubundle\staff\domain\v1\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class DivisionSchema
 *
 * @package yubundle\staff\domain\v1\repositories\schema
 *
 */
class DivisionSchema extends BaseSchema
{

    public function relations()
    {
        return [
            'parent' => [
                'type' => RelationEnum::ONE,
                'field' => 'parent_id',
                'foreign' => [
                    'id' => 'staff.division',
                    'field' => 'id',
                ],
            ],
            'child' => [
                'type' => RelationEnum::MANY,
                'field' => 'id',
                'foreign' => [
                    'id' => 'staff.division',
                    'field' => 'parent_id',
                ],
            ],
            'company' => [
                'type' => RelationEnum::ONE,
                'field' => 'company_id',
                'foreign' => [
                    'id' => 'staff.company',
                    'field' => 'id',
                ],
            ],
            'workers' => [
                'type' => RelationEnum::MANY,
                'field' => 'id',
                'foreign' => [
                    'id' => 'staff.worker',
                    'field' => 'division_id',
                ],
            ]
        ];
    }

}
