<?php

namespace tests\functional\v1\services;

use yii2tool\test\Test\BaseDomainTest;
use yii2tool\test\Test\Unit;
use Yii;
use yii\web\NotFoundHttpException;
use yii2rails\extension\jwt\entities\ProfileEntity;
use yubundle\account\domain\v2\exceptions\InvalidIpAddressException;
use yubundle\account\domain\v2\exceptions\NotFoundLoginException;

class TokenTest extends BaseDomainTest
{

	const IP = '192.168.44.92';
	const USER_ID = 3;

    public $package = 'vendor/yubundle/yii2-account';

	/*public function testForgeNotFoundLogin() {
		try {
			\App::$domain->account->token->forge(999999, self::IP);
			$this->tester->assertBad();
		} catch(NotFoundLoginException $e) {
			$this->tester->assertNice();
		}
	}*/

	public function testExpire() {
	    $profileEntity = new ProfileEntity;
        $profileEntity->life_time = 3600;
        $profileEntity->key = 'W4PpvVwI82Rfl9fl2R9XeRqBI0VFBHP3';
		$token = \App::$domain->account->token->forge(self::USER_ID, self::IP, $profileEntity);
		
		$this->tester->assertTrue($this->isValidateToken(self::USER_ID, $token, self::IP));
		$this->tester->assertTrue($this->isValidateToken(self::USER_ID, $token, self::IP));
	}

	public function _testDeleteOneByToken()
	{
		$token = \App::$domain->account->token->forge(self::USER_ID, self::IP);
		\App::$domain->account->token->deleteOneByToken($token);
		$this->tester->assertFalse($this->isValidateToken(self::USER_ID, $token, self::IP));
	}
	
	private function isValidateToken($userId, $token, $ip) {
		$this->tester->assertNotEmpty($token);
		$this->tester->assertNotEmpty($ip);
		try {
			$tokenEntity = \App::$domain->account->token->validate($token, $ip);
			$this->tester->assertEquals($tokenEntity->user_id, $userId);
			return true;
		} catch(InvalidIpAddressException $e) {
		
		} catch(NotFoundHttpException $e) {
		
		}
		return false;
	}

    private function assertLoginEntity(LoginEntity $loginEntity, $method) {
        $actual = $loginEntity->toArray();
        list($actual) = DataHelper::fakeCollectionValue([$actual], [
            'token' => 'jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjVkNTBjMDgyLTlhMWMtNGE3ZS1jYmQwLTQwZmUwMmI4ZTZjNiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3RcL3YxXC9hdXRoIiwic3ViamVjdCI6eyJpZCI6Nn0sImF1ZCI6WyJodHRwOlwvXC9hcGkueXVtYWlsLnByb2plY3QiXSwiZXhwIjoxNTUxMjgyMjMzfQ.4izUBoQtguLpvuzg3YA-49ZR1u7qzShPrKMskBeGF7k',
        ]);
        $this->assertArray($actual, $method);
    }
}
