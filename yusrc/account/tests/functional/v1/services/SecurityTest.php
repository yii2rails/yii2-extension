<?php

namespace tests\functional\v1\services;

use yii\helpers\ArrayHelper;
use yii2tool\test\Test\BaseDomainTest;
use yii2tool\test\Test\Unit;
use Yii;
use tests\functional\v1\enums\LoginEnum;
use yubundle\account\domain\v2\entities\LoginEntity;

class SecurityTest extends BaseDomainTest
{

    const ID_ADMIN = 1;

    public $package = 'vendor/yubundle/yii2-account';
    
	public function testOneById()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->security->oneById(self::ID_ADMIN);
        $actual = $entity->toArray();
        $this->assertArray($actual, __METHOD__);
	}
	
	public function testSecurityCheck()
	{
		$collection = \App::$domain->account->security->all();
        $actual = ArrayHelper::toArray($collection);
        $this->assertArray($actual, __METHOD__);
	}
	
    private function assertLoginEntity(LoginEntity $loginEntity, $method) {
        $actual = $loginEntity->toArray();
        list($actual) = DataHelper::fakeCollectionValue([$actual], [
            'token' => 'jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjVkNTBjMDgyLTlhMWMtNGE3ZS1jYmQwLTQwZmUwMmI4ZTZjNiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3RcL3YxXC9hdXRoIiwic3ViamVjdCI6eyJpZCI6Nn0sImF1ZCI6WyJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3QiXSwiZXhwIjoxNTUxMjgyMjMzfQ.4izUBoQtguLpvuzg3YA-49ZR1u7qzShPrKMskBeGF7k',
        ]);
        $this->assertArray($actual, $method);
    }

}
