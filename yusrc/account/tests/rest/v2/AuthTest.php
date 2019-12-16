<?php

namespace yubundle\account\tests\rest\v2;

use yubundle\user\tests\rest\v1\UserSchema;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2tool\test\enums\TypeEnum;
use yii2tool\test\helpers\TestHelper;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;

class AuthTest extends BaseActiveApiTest
{

    public $package = 'api';
    public $point = 'v1/auth';

    /*public function testCors()
    {
        if(TestHelper::isSkipMode(['dev', 'test'])) return;
        $requestEntity = new RequestEntity;
        //$requestEntity->uri = 'auth';
        $requestEntity->method = HttpMethodEnum::OPTIONS;
        $responseEntity = $this->sendRequest($requestEntity);
        //d($responseEntity);
        $this->tester->assertArraySubset([
            'Http-Code' => '200',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Expose-Headers' => 'Content-Type, Link, Access-token, Authorization, Time-Zone, X-Pagination-Total-Count, X-Pagination-Page-Count, X-Pagination-Current-Page, X-Pagination-Per-Page, X-Entity-Id',
            'Allow' => 'GET, POST, HEAD, OPTIONS',
            'Access-Control-Allow-Methods' => 'GET, POST, HEAD, OPTIONS',
        ], $responseEntity->headers);
    }*/

	public function testGuest()
	{
        AuthTestHelper::logout();

        $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::GET;
        $responseEntity = $this->sendRequest($requestEntity);
        $this->tester->assertEquals(401, $responseEntity->status_code);
	}

    public function testAuthByPhone()
    {
        AuthTestHelper::logout();

        $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::POST;
        $requestEntity->data = [
            'login' => '77771111111',
            'password' => 'Wwwqqq111',
        ];
        $responseEntity = $this->sendRequest($requestEntity);
        $this->tester->assertEquals(200, $responseEntity->status_code);
        $actual = $responseEntity->data;
        $this->tester->assertArrayType(AccountSchema::$identity, $actual);
        $this->tester->assertValueType(TypeEnum::ARRAY, $actual['roles']);
        $this->tester->assertValueType(TypeEnum::STRING, $actual['token']);
        $this->tester->assertArrayType(UserSchema::$person, $actual['person']);
        //$this->tester->assertArrayType(StaffSchema::$company, $actual['company']);
    }

    public function testAuthByLogin()
    {
        AuthTestHelper::logout();

        $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::POST;
        $requestEntity->data = [
            'login' => 'admin',
            'password' => 'Wwwqqq111',
        ];
        $responseEntity = $this->sendRequest($requestEntity);
        $this->tester->assertEquals(200, $responseEntity->status_code);
        $actual = $responseEntity->data;
        $this->tester->assertArrayType(AccountSchema::$identity, $actual);
        $this->tester->assertValueType(TypeEnum::ARRAY, $actual['roles']);
        $this->tester->assertValueType(TypeEnum::STRING, $actual['token']);
        $this->tester->assertArrayType(UserSchema::$person, $actual['person']);
        //$this->tester->assertArrayType(StaffSchema::$company, $actual['company']);
    }

    /*public function testAuthByEmail()
    {
        AuthTestHelper::logout();

        $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::POST;
        $requestEntity->data = [
            'login' => 'tester1@' . TestHelper::getServerConfig('mailDomainPersonal'),
            'password' => 'Wwwqqq111',
        ];
        $responseEntity = $this->sendRequest($requestEntity);
        $this->tester->assertEquals(200, $responseEntity->status_code);
        $actual = $responseEntity->data;
        $this->tester->assertArrayType(AccountSchema::$identity, $actual);
        //$this->tester->assertValueType(TypeEnum::ARRAY, $actual['roles']);
        $this->tester->assertValueType(TypeEnum::STRING, $actual['token']);
        $this->tester->assertArrayType(UserSchema::$person, $actual['person']);
        $this->tester->assertArrayType(StaffSchema::$company, $actual['company']);
    }*/

    public function testInfoAdmin()
    {
        AuthTestHelper::authByLogin('admin');

        $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::GET;
        $responseEntity = $this->sendRequest($requestEntity);
        $this->tester->assertEquals(200, $responseEntity->status_code);
        $actual = $responseEntity->data;
        $this->tester->assertArrayType(AccountSchema::$identity, $actual);
        $this->tester->assertValueType(TypeEnum::ARRAY, $actual['roles']);
        $this->tester->assertValueType(TypeEnum::NULL, $actual['token']);
    }

    /*public function testInfoTester1()
    {
        AuthTestHelper::authByLogin('tester1');

        $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::GET;
        $responseEntity = $this->sendRequest($requestEntity);
        $this->tester->assertEquals(200, $responseEntity->status_code);
        $actual = $responseEntity->data;
        $this->tester->assertArrayType(AccountSchema::$identity, $actual);
        $this->tester->assertValueType(TypeEnum::NULL, $actual['token']);
    }*/

}
