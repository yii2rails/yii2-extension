<?php

namespace yubundle\account\tests\rest\v2;

use yii2lab\notify\domain\helpers\test\NotifyTestHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2tool\test\enums\TypeEnum;
use yii2tool\test\helpers\TestHelper;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;
use yii2bundle\account\domain\v3\helpers\test\CurrentPhoneTestHelper;
use yii2bundle\account\domain\v3\helpers\test\RegistrationTestHelper;

class RegistrationTest extends BaseActiveApiTest
{

    const PASSWORD = 'Wwwqqq111';

    public $package = 'api';
    public $point = 'v1';

    public function testClearSms()
    {
        NotifyTestHelper::cleanSms();
    }

	public function testRequestActivationCode() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = RegistrationTestHelper::getlastPhone();
        CurrentPhoneTestHelper::set($phone);
        $this->createEntity('registration/request-activation-code', [
            'phone' => $phone,
        ]);
    }

    public function testVerifyActivationCode() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $code = NotifyTestHelper::getActivationCodeByPhone($phone);
        for($i = 0; $i < 6; $i++) {
            $requestEntity = new RequestEntity;
            $requestEntity->uri = 'registration/verify-activation-code';
            $requestEntity->method = HttpMethodEnum::POST;
            $requestEntity->data = [
                'phone' => $phone,
                'activation_code' => $code,
            ];
            $responseEntity = $this->sendRequest($requestEntity);
            $this->tester->assertEquals(204, $responseEntity->status_code);
        }
    }

    public function testVerifyActivationCodeBadCode() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $this->createEntityUnProcessible('registration/verify-activation-code', [
            'phone' => $phone,
            'activation_code' => '123456',
        ], ['activation_code']);
    }

    public function testVerifyActivationCodeBadPhone() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $this->createEntityUnProcessible('registration/verify-activation-code', [
            'phone' => '77772222222',
            'activation_code' => '111111',
        ], ['phone']);
    }

    /*public function testCreateAccountBadLogin() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $code = NotifyTestHelper::getActivationCodeByPhone($phone);
        $this->createEntityUnProcessible('registration/create-account', [
            'phone' => $phone,
            'activation_code' => $code,
            'password' => self::PASSWORD,
            'login' => 'test#' . $phone,
            'first_name' => '',
            'last_name' => 'Family',
            'middle_name' => 'Middle',
            'birthday' => '2018-03-20',
        ], ['login', 'first_name']);

        $this->createEntityUnProcessible('registration/create-account', [
            'phone' => $phone,
            'activation_code' => $code,
            'password' => self::PASSWORD,
            'login' => 'test.' . $phone,
            'first_name' => '',
            'last_name' => 'Family',
            'middle_name' => 'Middle',
            'birthday' => '2018-03-20',
        ], ['first_name']);

        $this->createEntityUnProcessible('registration/create-account', [
            'phone' => $phone,
            'activation_code' => $code,
            'password' => self::PASSWORD,
            'login' => 'test_' . $phone,
            'first_name' => '',
            'last_name' => 'Family',
            'middle_name' => 'Middle',
            'birthday' => '2018-03-20',
        ], ['first_name']);
    }

    public function testCreateAccount() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $code = NotifyTestHelper::getActivationCodeByPhone($phone);
        $this->createEntity('registration/create-account', [
            'phone' => $phone,
            'activation_code' => $code,
            'password' => self::PASSWORD,
            'login' => 'test' . $phone,
            'first_name' => 'Name',
            'last_name' => 'Family',
            'middle_name' => 'Middle',
            'birthday' => '2018-03-20',
        ]);
        $this->checkAuth($phone, self::PASSWORD);
    }*/

    public function testCreateAccountByAdmin() {
        AuthTestHelper::authByLogin('admin');

        $phone = RegistrationTestHelper::getlastPhone();
        CurrentPhoneTestHelper::set($phone);

        $this->createEntity('registration/create-account', [
            'phone' => $phone,
            //'activation_code' => '111111',
            'password' => self::PASSWORD,
            'login' => 'test' . $phone,
            'first_name' => 'Name',
            'last_name' => 'Family',
            //'middle_name' => 'Middle',
            'birthday' => '2018-03-20',
        ]);
        $this->checkAuth($phone, self::PASSWORD);
    }

    public function testVerifyActivationCodeBadCodeManyAttempts() {
        if(TestHelper::isSkipMode(['prod'])) return;

        $phone = RegistrationTestHelper::getlastPhone();
        CurrentPhoneTestHelper::set($phone);
        $this->createEntity('registration/request-activation-code', [
            'phone' => $phone,
        ]);

        for($i = 0; $i < 5; $i++) {
            $this->createEntityUnProcessible('registration/verify-activation-code', [
                'phone' => $phone,
                'activation_code' => '123456',
            ], ['activation_code']);
        }
        $this->createEntityUnProcessible('registration/verify-activation-code', [
            'phone' => $phone,
            'activation_code' => '123456',
        ], ['phone']);
    }

    /*public function testCreateAccountBadCodeManyAttempts() {
        if(TestHelper::isSkipMode(['prod'])) return;
        AuthTestHelper::logout();

        $phone = RegistrationTestHelper::getlastPhone();
        CurrentPhoneTestHelper::set($phone);
        $this->createEntity('registration/request-activation-code', [
            'phone' => $phone,
        ]);

        for($i = 0; $i < 5; $i++) {
            $this->createEntityUnProcessible('registration/create-account', [
                'phone' => $phone,
                'activation_code' => '111111',
                'password' => self::PASSWORD,
                'login' => 'test' . $phone,
                'first_name' => 'Name',
                'last_name' => 'Family',
                'middle_name' => 'Middle',
                'birthday' => '2018-03-20',
            ], ['activation_code']);
        }
        $this->createEntityUnProcessible('registration/create-account', [
            'phone' => $phone,
            'activation_code' => '111111',
            'password' => self::PASSWORD,
            'login' => 'test' . $phone,
            'first_name' => 'Name',
            'last_name' => 'Family',
            'middle_name' => 'Middle',
            'birthday' => '2018-03-20',
        ], ['phone']);
    }*/

}
