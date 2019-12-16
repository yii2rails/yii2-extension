<?php

namespace yubundle\staff\tests\rest\v1;

use yubundle\account\tests\rest\v2\AccountSchema;
use yubundle\staff\tests\rest\v1\StaffSchema;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;

class StaffWorkerTest extends BaseActiveApiTest
{

    public $package = 'api';
    public $point = 'v1';
    public $resource = 'staff-worker';

    public function testAll()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->readCollection($this->resource, [], StaffSchema::$worker, true);
    }

    public function testOne()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->readEntity($this->resource, 2, StaffSchema::$worker);
    }

    public function testRelation()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->assertRelationContract($this->resource, 2, [
            'user' => AccountSchema::$identity,
        ]);
    }

}
