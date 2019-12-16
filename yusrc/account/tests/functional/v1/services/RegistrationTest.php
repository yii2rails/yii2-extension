<?php

namespace tests\functional\v1\services;

use App;
use yii\web\NotFoundHttpException;
use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\BaseDomainTest;
use yii2tool\test\Test\Unit;
use tests\functional\v1\enums\LoginEnum;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\forms\LoginForm;
use yubundle\account\domain\v2\forms\registration\PersonInfoForm;
use yubundle\account\domain\v2\helpers\TestAuthHelper;
use yii2lab\notify\domain\entities\TestEntity;
use yii2lab\notify\domain\enums\TypeEnum;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2rails\domain\data\Query;
use yii2rails\extension\web\enums\HttpMethodEnum;

class RegistrationTest extends BaseDomainTest
{

    const PHONE = '77772222221';
    const PASSWORD = 'Wwwqqq111';

    public $package = 'vendor/yubundle/yii2-account';

    /*public function testRegistration()
    {
        \App::$domain->notify->test->truncate(TypeEnum::SMS);
        $this->requestActivationCode();
        $this->verifyActivationCode();
        $this->verifyActivationCodeBadPhone();
        $this->createAccount();
        $this->auth();
    }*/

    private function requestActivationCode() {
        $model = new PersonInfoForm;
        $model->phone = self::PHONE;
        App::$domain->account->registration->requestCode($model);
        \Yii::$app->queue->run(false);
    }

    private function verifyActivationCode() {
        $code = $this->getActivationCode();
        $model = new PersonInfoForm;
        $model->phone = self::PHONE;
        $model->activation_code = $code;
        App::$domain->account->registration->verifyCode($model);
    }

    private function verifyActivationCodeBadPhone() {
        $code = $this->getActivationCode();
        $model = new PersonInfoForm;
        $model->phone = '77772222222';
        $model->activation_code = $code;
        try {
            App::$domain->account->registration->verifyCode($model);
            $this->tester->assertBad();
        } catch(NotFoundHttpException $e) {
            $this->tester->assertExceptionMessage('Temp user not found', $e);
        }
    }

    private function createAccount() {
        $code = $this->getActivationCode();
        $model = new PersonInfoForm;
        $model->phone = self::PHONE;
        $model->activation_code = $code;
        $model->password = self::PASSWORD;
        $model->password_confirm = self::PASSWORD;
        $model->login = 'user1';
        $model->first_name = 'Name';
        $model->last_name = 'Family';
        $model->birthday_day = 20;
        $model->birthday_month = 3;
        $model->birthday_year = 2018;
        App::$domain->account->registration->createAccountWeb($model);
    }

    private function auth() {
        /** @var LoginEntity $entity */
        $loginForm = new LoginForm;
        $loginForm->login = self::PHONE;
        $loginForm->password = self::PASSWORD;
        $loginEntity = App::$domain->account->auth->authenticationFromApi($loginForm);
        $this->assertLoginEntity($loginEntity, __METHOD__);
    }

    private function assertLoginEntity(LoginEntity $loginEntity, $method) {
        $actual = $loginEntity->toArray();
        list($actual) = DataHelper::fakeCollectionValue([$actual], [
            'token' => 'jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjVkNTBjMDgyLTlhMWMtNGE3ZS1jYmQwLTQwZmUwMmI4ZTZjNiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3RcL3YxXC9hdXRoIiwic3ViamVjdCI6eyJpZCI6Nn0sImF1ZCI6WyJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3QiXSwiZXhwIjoxNTUxMjgyMjMzfQ.4izUBoQtguLpvuzg3YA-49ZR1u7qzShPrKMskBeGF7k',
        ]);
        $this->assertArray($actual, $method);
    }

    private function getActivationCode() {
        $query = Query::forge()
            ->where('type', TestEntity::TYPE_SMS)
            ->where('address', self::PHONE)
            ->orderBy(['created_at' => SORT_DESC]);
        /** @var TestEntity $sms */
        $sms = \App::$domain->notify->test->one($query);
        $code = '';
        if (preg_match('/([0-9]{6})/s', $sms->message, $matches)) {
            $code = $matches[1];
        }
        return $code;
    }

}
