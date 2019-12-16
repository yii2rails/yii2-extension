<?php

namespace yubundle\user\tests\rest\v1;

use yubundle\user\tests\rest\v1\UserSchema;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2tool\test\helpers\TestHelper;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2bundle\account\domain\v3\helpers\test\RegistrationTestHelper;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;

class PersonTest extends BaseActiveApiTest
{

    public $package = 'api';
    public $point = 'v1';
    public $resource = 'person';

    public function testGuest()
	{
        AuthTestHelper::logout();

        $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::GET;
        $requestEntity->uri = $this->resource;
        $responseEntity = $this->sendRequest($requestEntity);
        $this->tester->assertEquals(401, $responseEntity->status_code);
	}

    public function testAdmin()
    {
        AuthTestHelper::authByLogin('admin');
        $this->readEntity($this->resource, null, UserSchema::$person);
    }

    /*public function testInfoTester1()
    {
        AuthTestHelper::authByLogin('tester1');

        $this->authByNewUser();
        $this->readEntity(null, null, UserSchema::$person);
        $actual = $this->readEntity(null, null, UserSchema::$person, ['fields' => 'id', 'expand' => 'user']);
        $this->tester->assertArrayType(UserSchema::$identity, $actual['user']);
    }*/

}
