<?php

namespace yii2rails\extension\changelog\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class WordSchema
 * 
 * @package yii2rails\extension\changelog\repositories\schema
 * 
 */
class WordSchema extends BaseSchema {

    public function relations()
    {
        return [
            'type' => [
                'type' => RelationEnum::ONE,
                'field' => 'type_name',
                'foreign' => [
                    'id' => 'changelog.type',
                    'field' => 'name',
                ],
            ],
        ];
    }

}
