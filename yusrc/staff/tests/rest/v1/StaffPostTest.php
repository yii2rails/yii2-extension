<?php

namespace yubundle\staff\tests\rest\v1;

use yubundle\staff\tests\rest\v1\StaffSchema;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;

class StaffPostTest extends BaseActiveApiTest
{

    public $package = 'api';
    public $point = 'v1';
    public $resource = 'staff-post';

    public function testAll()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->readCollection($this->resource, [], StaffSchema::$post, 11);
    }

    public function testOne()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->readEntity($this->resource, 71, StaffSchema::$post);
    }

}
