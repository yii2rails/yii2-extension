<?php

namespace yubundle\user\domain\v1\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class PersonSchema
 * 
 * @package yubundle\user\domain\v1\repositories\schema
 * 
 */
class PersonSchema extends BaseSchema {

    public function relations()
    {
        return [
            'worker' => [
                'type' => RelationEnum::ONE,
                'field' => 'id',
                'foreign' => [
                    'id' => 'staff.worker',
                    'field' => 'person_id',
                ],
            ],
            'user' => [
                'type' => RelationEnum::ONE,
                'field' => 'id',
                'foreign' => [
                    'id' => 'account.login',
                    'field' => 'person_id',
                ],
            ],
            'sex' => [
                'type' => RelationEnum::ONE,
                'field' => 'sex_id',
                'foreign' => [
                    'id' => 'reference.item',
                    'field' => 'id',
                ],
            ],
        ];
    }

}
