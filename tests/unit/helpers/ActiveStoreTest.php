<?php
namespace tests\unit\helpers;

use yii2tool\test\Test\Unit;
use yii2rails\extension\tests\models\Login;

class ActiveStoreTest extends Unit
{
	
	public function testOne()
	{
		$result = Login::one(['login' => 'admin']);
		$this->tester->assertEquals($result, [
			'login' => 'admin',
			'role' => 'rAdministrator',
			'is_active' => 1,
		]);
	}
	
	public function testAll()
	{
		$result = Login::all(['is_active' => 1]);
		$this->tester->assertEquals($result, [
			[
				'login' => 'admin',
				'role' => 'rAdministrator',
				'is_active' => 1,
			],
			[
				'login' => 'tester1',
				'role' => 'rUnknownUser',
				'is_active' => 1,
			],
		]);
	}
	
}
