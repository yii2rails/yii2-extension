<?php

namespace yubundle\staff\domain\v1\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class CompanySchema
 * 
 * @package yubundle\staff\domain\v1\repositories\schema
 * 
 */
class CompanySchema extends BaseSchema {

    public function relations()
    {
        return [
            'domains' => [
                'type' => RelationEnum::MANY,
                'field' => 'id',
                'foreign' => [
                    'id' => 'mail.companyDomain',
                    'field' => 'company_id',
                ],
            ],
        ];
    }

}
