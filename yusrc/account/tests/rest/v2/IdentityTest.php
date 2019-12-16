<?php

namespace yubundle\account\tests\rest\v2;

use yubundle\staff\tests\rest\v1\StaffSchema;
use yii\helpers\ArrayHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2tool\test\enums\TypeEnum;
use yii2tool\test\helpers\TestHelper;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;
use yubundle\user\tests\rest\v1\UserSchema;

class IdentityTest extends BaseActiveApiTest
{

    public $package = 'api';
    public $point = 'v1';
    public $resource = 'identity';

    public function testAll()
    {
        AuthTestHelper::authByLogin('admin');
        $this->readCollection($this->resource, [], AccountSchema::$identity);
    }

    public function testAllByPhone()
    {
        AuthTestHelper::authByLogin('admin');
        $this->readCollection($this->resource, ['phone' => '77771111111'], AccountSchema::$identity, 1);
    }

    public function testAllByLogin()
    {
        AuthTestHelper::authByLogin('admin');
        $this->readCollection($this->resource, ['login' => 'admin'], AccountSchema::$identity, 1);
    }

    public function testOne()
    {
        AuthTestHelper::authByLogin('admin');
        $this->readEntity($this->resource, 2, AccountSchema::$identity);
    }

    public function testOneSecurity()
    {
        AuthTestHelper::authByLogin('admin');
        $actual = $this->readEntity($this->resource, 2, AccountSchema::$identity, ['expand' => 'security']);

        $token = ArrayHelper::getValue($actual, 'security.token');
        $passwordHash = ArrayHelper::getValue($actual, 'security.password_hash');
        $this->tester->assertEquals(null, $token);
        $this->tester->assertEquals(null, $passwordHash);
    }

    public function testRelation()
    {
        AuthTestHelper::authByLogin('admin');
        $this->assertRelationContract($this->resource, 2, [
            'person' => UserSchema::$person,
            //'company' => StaffSchema::$company,
        ]);
    }

}
