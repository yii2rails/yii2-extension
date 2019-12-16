<?php

namespace yubundle\reference\domain\repositories\schema;

use yii2rails\domain\repositories\relations\BaseSchema;
use yii2rails\domain\enums\RelationClassTypeEnum;
use yii2rails\domain\enums\RelationEnum;

/**
 * Class EnumSchema
 * 
 * @package yubundle\reference\domain\repositories\schema
 * 
 */
class EnumSchema extends BaseSchema {

    public function relations()
    {
        return [
            'localization' => [
                'type' => RelationEnum::ONE,
                'classType' => RelationClassTypeEnum::SERVICE,
                'field' => 'id',
                'foreign' => [
                    'id' => 'reference.localization',
                    'field' => 'reference_item_id',
                ],
            ],
            'localizations' => [
                'type' => RelationEnum::MANY,
                'classType' => RelationClassTypeEnum::SERVICE,
                'field' => 'id',
                'foreign' => [
                    'id' => 'reference.localization',
                    'field' => 'reference_item_id',
                ],
            ],
        ];
    }

}
