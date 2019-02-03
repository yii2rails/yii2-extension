<?php

namespace yii2rails\extension\package\domain\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class GroupSchema
 * 
 * @package yii2rails\extension\package\domain\repositories\schema
 * 
 */
class GroupSchema extends BaseSchema {

    public function relations()
    {
        return [
            'provider' => [
                'type' => RelationEnum::ONE,
                'field' => 'provider_name',
                'foreign' => [
                    'id' => 'package.provider',
                    'field' => 'name',
                ],
            ],
        ];
    }
}
