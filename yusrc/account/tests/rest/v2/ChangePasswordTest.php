<?php

namespace yubundle\account\tests\rest\v2;

use yii2lab\rest\domain\entities\RequestEntity;
use yii2tool\test\helpers\TestHelper;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;
use yii2bundle\account\domain\v3\helpers\test\CurrentPhoneTestHelper;
use yii2bundle\account\domain\v3\helpers\test\RegistrationTestHelper;

class ChangePasswordTest extends BaseActiveApiTest
{

    const PHONE = '7777111111';
    const PASSWORD = 'Wwwqqq111';
    const NEW_PASSWORD = 'Wwwqqq222';

    public $package = 'api';
    public $point = 'v1';
    public $resource = 'security/password';

    public function testCreateUser()
    {
        RegistrationTestHelper::registration();
    }

    public function testChangePassword() {
        $phone = CurrentPhoneTestHelper::get();
        AuthTestHelper::authByLogin('test' . $phone);

        $requestEntity = new RequestEntity;
        $requestEntity->uri = $this->resource;
        $requestEntity->method = HttpMethodEnum::PUT;
        $requestEntity->data = [
            'password' => self::PASSWORD,
            'new_password' => self::NEW_PASSWORD,
        ];
        $responseEntity = $this->sendRequest($requestEntity);
        $this->tester->assertEquals(204, $responseEntity->status_code);
        $this->checkAuth($phone, self::NEW_PASSWORD);
    }

}
