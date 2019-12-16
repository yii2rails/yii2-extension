<?php

namespace yubundle\storage\domain\v1\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class FileExtensionSchema
 * 
 * @package yubundle\storage\domain\v1\repositories\schema
 * 
 */
class FileExtensionSchema extends BaseSchema {

    public function relations()
    {
        return [
            'type' => [
                'type' => RelationEnum::ONE,
                'field' => 'type_id',
                'foreign' => [
                    'id' => 'storage.fileType',
                    'field' => 'id',
                ],
            ],
        ];
    }

}
