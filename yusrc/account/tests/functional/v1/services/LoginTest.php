<?php

namespace tests\functional\v1\services;

use yii\helpers\ArrayHelper;
use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\BaseDomainTest;
use yii2tool\test\Test\Unit;
use yii\web\NotFoundHttpException;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use tests\functional\v1\enums\LoginEnum;
use yubundle\account\domain\v2\entities\LoginEntity;

class LoginTest extends BaseDomainTest
{

    const ID_ADMIN = 1;
    const ID_NOT_EXISTS = 99999;
    const LOGIN_NOT_EXISTS = '77771111118';

    public $package = 'vendor/yubundle/yii2-account';
	
	public function testOneByLogin()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->login->oneByLogin('admin');
        $this->assertLoginEntity($entity, __METHOD__);
	}
	
	public function testOne()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->login->oneById(self::ID_ADMIN);
        $this->assertLoginEntity($entity, __METHOD__);
	}
	
	public function testOneWithRelation()
	{
		$query = Query::forge();
		//$query->with('assignments');
		$query->with('security');
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->login->oneById(self::ID_ADMIN, $query);

        $this->assertLoginEntity($entity, __METHOD__);
	}
	
	public function testOneByLoginNotFound()
	{
		try {
			\App::$domain->account->login->oneByLogin(self::LOGIN_NOT_EXISTS);
			$this->tester->assertBad();
		} catch(NotFoundHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testOneByIdNotFound()
	{
		try {
			\App::$domain->account->login->oneById(self::ID_NOT_EXISTS);
			$this->tester->assertBad();
		} catch(NotFoundHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testAll()
	{
		/** @var BaseEntity[] $collection */
		$query = Query::forge();
		$collection = \App::$domain->account->login->all($query);
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
