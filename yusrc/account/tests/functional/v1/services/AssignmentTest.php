<?php

namespace tests\functional\v1\services;

use yii\helpers\ArrayHelper;
use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\BaseDomainTest;
use yii2tool\test\Test\Unit;
use Yii;
use yii2rails\domain\data\Query;
use tests\functional\v1\enums\LoginEnum;
use yubundle\account\domain\v2\entities\AssignmentEntity;

class AssignmentTest extends BaseDomainTest
{

    const ID_ADMIN = 1;
    const ID_USER_2 = 2;

    public $package = 'vendor/yii2rails/yii2-extension/yusrc/account';

	public function testAll()
	{
		/** @var AssignmentEntity[] $collection */
		$query = Query::forge();
		$query->where('user_id', self::ID_ADMIN);
		$collection = \App::$domain->rbac->assignment->all($query);
        $actual = ArrayHelper::toArray($collection);
        $this->assertArray($actual, __METHOD__);
	}
	
	public function testAll2()
	{
		/** @var AssignmentEntity[] $collection */
		$query = Query::forge();
		$query->where('user_id', self::ID_USER_2);
		$collection = \App::$domain->rbac->assignment->all($query);
        $actual = ArrayHelper::toArray($collection);
        $this->assertArray($actual, __METHOD__);
	}
	
	public function testAllAssignments()
	{
		/** @var AssignmentEntity[] $collection */
		$collection = \App::$domain->rbac->assignment->getAssignments(self::ID_USER_2);
        $actual = ArrayHelper::toArray($collection);
        $this->assertArray($actual, __METHOD__);
	}
	
	public function testIsHasRole()
	{
		$isHas = \App::$domain->rbac->assignment->isHasRole(self::ID_ADMIN, 'rAdministrator');
		$this->tester->assertTrue($isHas);
		
		$isHas = \App::$domain->rbac->assignment->isHasRole(self::ID_USER_2, 'rResmiUnknownUser');
		$this->tester->assertFalse($isHas);
	}
	
	public function testIsHasRoleNegative()
	{
		$isHas = \App::$domain->rbac->assignment->isHasRole(self::ID_USER_2, 'rAdministrator');
		$this->tester->assertFalse($isHas);
	}
	
	public function _testAllUserIdsByRole()
	{
        $actual = \App::$domain->rbac->assignment->getUserIdsByRole('rUnknownUser');
        $this->assertArray($actual, __METHOD__ . 'UnknownUser');

        $actual = \App::$domain->rbac->assignment->getUserIdsByRole('rAdministrator');
        $this->assertArray($actual, __METHOD__ . 'Administrator');
	}
	
}
