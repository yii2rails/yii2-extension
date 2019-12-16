<?php

namespace tests\functional\v1\services;

use App;
use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\BaseDomainTest;
use yii2tool\test\Test\Unit;
use tests\functional\v1\enums\LoginEnum;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\forms\LoginForm;
use yubundle\account\domain\v2\helpers\TestAuthHelper;

class AuthTest extends BaseDomainTest
{

    const ID_USER = 3;
    const LOGIN_ADMIN = '77771111111';
    const LOGIN_NOT_EXISTS = '77771111118';

    const TOKEN_NOT_INCORRECT = '5f6bbd8ea39e34f212d432a968be2abe';

    const PASSWORD = 'Wwwqqq111';
    const PASSWORD_INCORRECT = 'Wwwqqq222';
    
    public $package = 'vendor/yubundle/yii2-account';
    
	public function testAuthentication()
	{
		/** @var LoginEntity $entity */
        $loginForm = new LoginForm;
        $loginForm->login = self::LOGIN_ADMIN;
        $loginForm->password = self::PASSWORD;
        $loginEntity = \App::$domain->account->auth->authenticationFromApi($loginForm);
        $this->assertLoginEntity($loginEntity, __METHOD__);
	}

    public function testByPhone() {
        $model = new LoginForm;
        $model->login = '77771111111';
        $model->password = 'Wwwqqq111';
        $loginEntity = App::$domain->account->auth->authenticationFromApi($model);
        $this->assertLoginEntity($loginEntity, __METHOD__);
    }

    public function testByLogin() {
        $model = new LoginForm;
        $model->login = 'admin';
        $model->password = 'Wwwqqq111';
        $loginEntity = App::$domain->account->auth->authenticationFromApi($model);
        $this->assertLoginEntity($loginEntity, __METHOD__);
    }

    /*public function testByEmail() {
        $model = new LoginForm;
        $model->login = 'admin@yuwert.kz';
        $model->password = 'Wwwqqq111';
        $loginEntity = App::$domain->account->auth->authenticationFromApi($model);
        $this->assertLoginEntity($loginEntity, __METHOD__);
    }

	public function testAuthenticationBadPassword()
	{
		try {
            $loginForm = new LoginForm;
            $loginForm->login = self::LOGIN_ADMIN;
            $loginForm->password = self::PASSWORD_INCORRECT;
            \App::$domain->account->auth->authenticationFromApi($loginForm);
			$this->tester->assertBad();
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertUnprocessableEntityHttpException(['password' => 'Incorrect login or password'], $e);
		}
	}*/
	
	public function testAuthenticationNotFoundUser()
	{
		try {
            $loginForm = new LoginForm;
            $loginForm->login = self::LOGIN_NOT_EXISTS;
            $loginForm->password = self::PASSWORD;
            \App::$domain->account->auth->authenticationFromApi($loginForm);
			$this->tester->assertBad();
		} catch(UnprocessableEntityHttpException $e) {
			$this->tester->assertUnprocessableEntityHttpException(['password' => 'Incorrect login or password'], $e);
		}
	}
	
	public function testAuthenticationByToken()
	{
		/** @var LoginEntity $loginEntity */

        $loginForm = new LoginForm;
        $loginForm->login = self::LOGIN_ADMIN;
        $loginForm->password = self::PASSWORD;
        $loginEntity = \App::$domain->account->auth->authenticationFromApi($loginForm);

		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->auth->authenticationByToken($loginEntity->token);
        $this->assertLoginEntity($entity, __METHOD__);
	}
	
	public function testAuthenticationByBadToken()
	{
		try {
			/** @var LoginEntity $entity */
            \App::$domain->account->auth->authenticationByToken(self::TOKEN_NOT_INCORRECT);
			$this->tester->assertBad();
		} catch(UnauthorizedHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testDenyAccess()
	{
		TestAuthHelper::authById(self::ID_USER);
		try {
			\App::$domain->account->auth->denyAccess();
			$this->tester->assertBad();
		} catch(ForbiddenHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testDenyAccessForGuest()
	{
		\App::$domain->account->auth->denyAccess();
		// for console
	}
	
	public function testLoginRequired()
	{
		\App::$domain->account->auth->loginRequired();
		// for console
	}

    private function assertLoginEntity(LoginEntity $loginEntity, $method) {
        $actual = $loginEntity->toArray();
        list($actual) = DataHelper::fakeCollectionValue([$actual], [
            'token' => 'jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjVkNTBjMDgyLTlhMWMtNGE3ZS1jYmQwLTQwZmUwMmI4ZTZjNiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3RcL3YxXC9hdXRoIiwic3ViamVjdCI6eyJpZCI6Nn0sImF1ZCI6WyJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3QiXSwiZXhwIjoxNTUxMjgyMjMzfQ.4izUBoQtguLpvuzg3YA-49ZR1u7qzShPrKMskBeGF7k',
        ]);
        $this->assertArray($actual, $method);
    }
}
