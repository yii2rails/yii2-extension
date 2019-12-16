<?php

namespace yubundle\reference\tests\rest\v1;

use yii2tool\test\enums\TypeEnum;

class ReferenceSchema
{

    public static $enum = [
        'id' => TypeEnum::INTEGER,
        'title' => TypeEnum::STRING,
        'name' => TypeEnum::STRING,
        'sort' => [TypeEnum::INTEGER, TypeEnum::NULL],
        'book_id' => TypeEnum::INTEGER,
        'created_at' => TypeEnum::TIME,
        'updated_at' => TypeEnum::TIME,
    ];

    public static $item = [
        'id' => TypeEnum::INTEGER,
    ];

}
