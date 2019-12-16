<?php

namespace yubundle\staff\tests\rest\v1;

use yubundle\staff\tests\rest\v1\StaffSchema;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;

class StaffDivisionTest extends BaseActiveApiTest
{

    public $package = 'api';
    public $point = 'v1';
    public $resource = 'staff-division';

    public function testAll()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->readCollection($this->resource, [], StaffSchema::$division, 11);
    }

    public function testOne()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->readEntity($this->resource, 11, StaffSchema::$division);
    }

    public function testRelation()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->assertRelationContract($this->resource, 11, [
            'company' => StaffSchema::$company,
            'child' => [StaffSchema::$division],
        ]);
    }

    public function testRelation2()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->assertRelationContract($this->resource, 13, [
            'company' => StaffSchema::$company,
            'workers' => [StaffSchema::$worker],
            'parent' => StaffSchema::$division,
        ]);
    }

}
