<?php

namespace yubundle\storage\tests\rest\v1;

use yii2tool\test\enums\TypeEnum;

class StorageSchema
{

    public static $file = [
        'id' => TypeEnum::INTEGER,
        'service_id' => TypeEnum::INTEGER,
        'entity_id' => TypeEnum::INTEGER,
        'editor_id' => TypeEnum::INTEGER,
        'hash' => TypeEnum::STRING,
        'extension' => TypeEnum::STRING,
        'name' => TypeEnum::STRING,
        'size' => TypeEnum::INTEGER,
        'description' => [TypeEnum::STRING, TypeEnum::NULL],
        'status' => TypeEnum::INTEGER,
        'directory' => TypeEnum::STRING,
        'file_name' => TypeEnum::STRING,
        'file_path' => TypeEnum::STRING,
        'created_at' => TypeEnum::TIME,
        'updated_at' => TypeEnum::TIME,
    ];

    public static $extension = [
        'id' => TypeEnum::INTEGER,
        'code' => TypeEnum::STRING,
        'mime' => TypeEnum::STRING,
        'status' => TypeEnum::INTEGER,
        'created_at' => TypeEnum::TIME,
        'updated_at' => TypeEnum::TIME,
    ];

}
