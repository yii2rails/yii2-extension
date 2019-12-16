<?php

namespace yubundle\storage\domain\v1\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class FileImageSchema
 * 
 * @package yubundle\storage\domain\v1\repositories\schema
 * 
 */
class FileImageSchema extends BaseSchema {

    public function relations()
    {
        return [
            'file' => [
                'type' => RelationEnum::ONE,
                'field' => 'file_id',
                'foreign' => [
                    'id' => 'storage.file',
                    'field' => 'id',
                ],
            ],
        ];
    }

}
