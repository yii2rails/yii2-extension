<?php

namespace yubundle\user\tests\rest\v1;

use yii2tool\test\enums\TypeEnum;

class UserSchema
{

    public static $person = [
        'id' => TypeEnum::INTEGER,
        'first_name' => TypeEnum::STRING,
        'last_name' => TypeEnum::STRING,
        'middle_name' => [TypeEnum::STRING, TypeEnum::NULL],
        'email' => [TypeEnum::STRING, TypeEnum::NULL],
        'phone' => TypeEnum::STRING,
        'birthday' => TypeEnum::STRING,
        //'worker' => TypeEnum::NULL,
        'user' => [TypeEnum::NULL, TypeEnum::ARRAY],
        'status' => TypeEnum::INTEGER,
        'full_name' => TypeEnum::STRING,
        'initial' => TypeEnum::STRING,
    ];

}
