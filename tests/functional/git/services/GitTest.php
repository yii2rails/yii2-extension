<?php

namespace tests\functional\git\services;

use yii2rails\domain\helpers\DomainHelper;
use yii2rails\extension\git\domain\entities\BranchEntity;
use yii2rails\extension\git\domain\entities\GitEntity;
use yii2rails\extension\git\domain\entities\RemoteEntity;
use yii2tool\test\Test\Unit;

class GitTest extends Unit {
	
	const PACKAGE = 'yii2rails/yii2-extension';
	
	protected function _before() {
		parent::_before();
		DomainHelper::forgeDomains([
			'git' => 'yii2rails\extension\git\domain\Domain',
			'package' => 'yii2rails\extension\package\domain\Domain',
		]);
	}
	
	public function testOneByDir() {
		return;
		
		$dir = 'C:\OpenServer\domains\yii\all\vendor\yii2rails\yii2-extension';
		/** @var GitEntity $gitEntity */
		$gitEntity = \App::$domain->git->git->oneByDir($dir);
		$this->tester->assertEquals($dir, $gitEntity->dir);
		$this->tester->assertIsCollection($gitEntity->remotes, RemoteEntity::class);
		$this->tester->assertIsCollection($gitEntity->branches, BranchEntity::class);
	}
	
	public function testAll() {
		return;
		
		/** @var GitEntity[] $gitEntity */
		$gitCollection = \App::$domain->git->git->all();
		$this->tester->assertIsEntity($gitCollection[0], GitEntity::class);
	}
	
}
