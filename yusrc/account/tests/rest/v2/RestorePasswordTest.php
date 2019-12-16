<?php

namespace yubundle\account\tests\rest\v2;

use yii2lab\notify\domain\helpers\test\NotifyTestHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2tool\test\helpers\TestHelper;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2bundle\account\domain\v3\helpers\test\CurrentPhoneTestHelper;
use yii2bundle\account\domain\v3\helpers\test\RegistrationTestHelper;

class RestorePasswordTest extends BaseActiveApiTest
{

    const PHONE = '7777111111';
    const PASSWORD = 'Wwwqqq111';
    const NEW_PASSWORD = 'Wwwqqq222';

    public $package = 'api';
    public $point = 'v1';

    public function testClearSms()
    {
        NotifyTestHelper::cleanSms();
    }

    public function testCreateUser()
    {
        if(TestHelper::isSkipMode(['prod'])) return;
        RegistrationTestHelper::registration();
    }

    public function testRequestActivationCode() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $this->createEntity('restore-password/request-activation-code', [
            'phone' => $phone,
        ]);
        $this->createEntityUnProcessible('restore-password/request-activation-code', [
            'phone' => $phone,
        ], ['phone']);
    }

    public function testVerifyActivationCode() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $code = NotifyTestHelper::getActivationCodeByPhone($phone);

        for($i = 0; $i < 6; $i++) {
            $requestEntity = new RequestEntity;
            $requestEntity->uri = 'restore-password/verify-activation-code';
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
        $this->createEntityUnProcessible('restore-password/verify-activation-code', [
            'phone' => $phone,
            'activation_code' => '123456',
        ], ['activation_code']);
    }

    public function testSetPassword() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $code = NotifyTestHelper::getActivationCodeByPhone($phone);
        $this->createEntity('restore-password/set-password', [
            'phone' => $phone,
            'activation_code' => $code,
            'password' => self::NEW_PASSWORD,
        ]);
        $this->createEntityUnProcessible('restore-password/set-password', [
            'phone' => $phone,
            'activation_code' => $code,
            'password' => self::NEW_PASSWORD,
        ], ['phone']);
        $this->checkAuth($phone, self::NEW_PASSWORD);
    }

    /*public function testVerifyActivationCodeBadCodeManyAttempts() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $this->createEntity('restore-password/request-activation-code', [
            'phone' => $phone,
        ]);

        for($i = 0; $i < 5; $i++) {
            $this->createEntityUnProcessible('restore-password/verify-activation-code', [
                'phone' => $phone,
                'activation_code' => '123456',
            ], ['activation_code']);
        }
        $this->createEntityUnProcessible('restore-password/verify-activation-code', [
            'phone' => $phone,
            'activation_code' => '123456',
        ], ['phone']);
    }

    public function testSetPasswordBadCodeManyAttempts() {
        if(TestHelper::isSkipMode(['prod'])) return;
        $phone = CurrentPhoneTestHelper::get();
        $this->createEntity('restore-password/request-activation-code', [
            'phone' => $phone,
        ]);

        for($i = 0; $i < 5; $i++) {
            $this->createEntityUnProcessible('restore-password/set-password', [
                'phone' => $phone,
                'activation_code' => '111111',
                'password' => self::NEW_PASSWORD,
            ], ['activation_code']);
        }
        $this->createEntityUnProcessible('restore-password/set-password', [
            'phone' => $phone,
            'activation_code' => '111111',
            'password' => self::NEW_PASSWORD,
        ], ['phone']);
    }*/

}
