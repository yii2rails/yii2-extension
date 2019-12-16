<?php

namespace yubundle\account\tests\rest\v2;

use yii2tool\test\enums\TypeEnum;

class AccountSchema
{

    public static $identity = [
        'id' => TypeEnum::INTEGER,
        'login' => TypeEnum::STRING,
        'status' => TypeEnum::INTEGER,
        'roles' => [TypeEnum::ARRAY, TypeEnum::NULL],
        'token' => [TypeEnum::STRING, TypeEnum::NULL],
        'person_id' => TypeEnum::INTEGER,
        'company_id' => TypeEnum::INTEGER,
        'person' => [TypeEnum::ARRAY, TypeEnum::NULL],
        'company' => [TypeEnum::ARRAY, TypeEnum::NULL],
    ];

}
