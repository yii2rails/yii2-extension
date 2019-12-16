<?php

namespace yubundle\storage\domain\v1\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class ServiceSchema
 * 
 * @package yubundle\storage\domain\v1\repositories\schema
 * 
 */
class ServiceSchema extends BaseSchema {

    public function relations()
    {
        return [
            'thumbs' => [
                'type' => RelationEnum::MANY,
                'field' => 'id',
                'foreign' => [
                    'id' => 'storage.serviceThumb',
                    'field' => 'service_id',
                ],
            ],
        ];
    }

}
