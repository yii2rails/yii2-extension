<?php

namespace yubundle\staff\tests\rest\v1;

use yii2tool\test\enums\TypeEnum;

class StaffSchema
{

    public static $company = [
        'id' => TypeEnum::INTEGER,
        'code' => TypeEnum::INTEGER,
        'name' => TypeEnum::STRING,
        'status' => TypeEnum::INTEGER,
        'created_at' => TypeEnum::TIME,
        'updated_at' => TypeEnum::TIME,
        //'domains' => TypeEnum::NULL,
    ];

    public static $worker = [
        'id' => TypeEnum::INTEGER,
        'person_id' => TypeEnum::INTEGER,
        'company_id' => TypeEnum::INTEGER,
        'email' => TypeEnum::STRING,
        'post_id' => TypeEnum::INTEGER,
        'division_id' => TypeEnum::INTEGER,
        'office' => TypeEnum::STRING,
        'phone' => TypeEnum::STRING,
        'status' => TypeEnum::INTEGER,
        'created_at' => TypeEnum::TIME,
        'updated_at' => TypeEnum::TIME,
        'corporate_email' => TypeEnum::STRING,
        'full_name' => [TypeEnum::STRING, TypeEnum::NULL],
        'division_name' => [TypeEnum::STRING, TypeEnum::NULL],
        'post_name' => [TypeEnum::STRING, TypeEnum::NULL],
        //"user": null,
    ];

    public static $division = [
        'id' => TypeEnum::INTEGER,
        'parent_id' => [TypeEnum::INTEGER, TypeEnum::NULL],
        'company_id' => TypeEnum::INTEGER,
        'name' => TypeEnum::STRING,
        'status' => TypeEnum::INTEGER,
        'created_at' => TypeEnum::TIME,
        'updated_at' => TypeEnum::TIME,
    ];

    public static $post = [
        'id' => TypeEnum::INTEGER,
        'title' => TypeEnum::STRING,
        'name' => TypeEnum::STRING,
        'sort' => [TypeEnum::INTEGER, TypeEnum::NULL],
        'book_id' => TypeEnum::INTEGER,
        'created_at' => TypeEnum::TIME,
        'updated_at' => TypeEnum::TIME,
    ];

}
