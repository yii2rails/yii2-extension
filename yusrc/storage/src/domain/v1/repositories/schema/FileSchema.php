<?php

namespace yubundle\storage\domain\v1\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class FileSchema
 * 
 * @package yubundle\storage\domain\v1\repositories\schema
 * 
 */
class FileSchema extends BaseSchema {

    public function relations()
    {
        return [
            'service' => [
                'type' => RelationEnum::ONE,
                'field' => 'service_id',
                'foreign' => [
                    'id' => 'storage.service',
                    'field' => 'id',
                ],
            ],
            'editor' => [
                'type' => RelationEnum::ONE,
                'field' => 'editor_id',
                'foreign' => [
                    'id' => 'user.person',
                    'field' => 'id',
                ],
            ],
            'ext' => [
                'type' => RelationEnum::ONE,
                'field' => 'extension',
                'foreign' => [
                    'id' => 'storage.fileExtension',
                    'field' => 'code',
                ],
            ],
        ];
    }

}
