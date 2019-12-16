<?php

namespace tests\unit\v1\helpers;

use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\BaseDomainTest;
use yii2tool\test\Test\Unit;
use tests\functional\v1\enums\LoginEnum;
use Yii;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\helpers\TestAuthHelper;

class TestAuthHelperTest extends BaseDomainTest
{

    public $package = 'vendor/yubundle/yii2-account';

	public function testAuthById()
	{
		TestAuthHelper::authById(1);
		/** @var LoginEntity $loginEntity */
		$loginEntity = Yii::$app->user->identity;
        $this->assertLoginEntity($loginEntity, __METHOD__);
	}

	public function testAuthByLogin()
	{
		TestAuthHelper::authByLogin('admin');
		/** @var LoginEntity $loginEntity */
		$loginEntity = Yii::$app->user->identity;
        $this->assertLoginEntity($loginEntity, __METHOD__);
	}

    private function assertLoginEntity(LoginEntity $loginEntity, $method) {
        $actual = $loginEntity->toArray();
        list($actual) = DataHelper::fakeCollectionValue([$actual], [
            'token' => 'jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjVkNTBjMDgyLTlhMWMtNGE3ZS1jYmQwLTQwZmUwMmI4ZTZjNiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3RcL3YxXC9hdXRoIiwic3ViamVjdCI6eyJpZCI6Nn0sImF1ZCI6WyJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3QiXSwiZXhwIjoxNTUxMjgyMjMzfQ.4izUBoQtguLpvuzg3YA-49ZR1u7qzShPrKMskBeGF7k',
        ]);
        $this->assertArray($actual, $method);
    }
}
