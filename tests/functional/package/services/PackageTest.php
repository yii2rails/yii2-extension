<?php

namespace tests\functional\package\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\helpers\DomainHelper;
use yii2rails\extension\package\domain\entities\ConfigEntity;
use yii2rails\extension\package\domain\entities\GroupEntity;
use yii2rails\extension\package\domain\entities\PackageEntity;
use yii2tool\test\Test\Unit;

class PackageTest extends Unit {
	
	const PACKAGE = 'yii2rails/yii2-extension';
	
	protected function _before() {
		parent::_before();
		DomainHelper::forgeDomains([
			'git' => 'yii2rails\extension\git\domain\Domain',
			'package' => 'yii2rails\extension\package\domain\Domain',
		]);
	}
	
	public function testAll() {
		/** @var PackageEntity[] $collection */
		$collection = \App::$domain->package->package->all();
		$this->tester->assertIsEntity($collection[0], PackageEntity::class);
	}
	
	public function testAllWithRel() {
		$query = Query::forge();
		$query->with('group');
		$query->with('config');
		/** @var PackageEntity[] $collection */
		$collection = \App::$domain->package->package->all($query);
		$this->tester->assertIsEntity($collection[0], PackageEntity::class);
		$this->tester->assertIsEntity($collection[0]->group, GroupEntity::class);
		$this->tester->assertIsEntity($collection[0]->config, ConfigEntity::class);
	}
	
	public function testOneById() {
		$entity = \App::$domain->package->package->oneById('yii2rails/yii2-extension');
		$this->tester->assertIsEntity($entity, PackageEntity::class);
	}
	
	public function testOneByIdWithRel() {
		$query = Query::forge();
		$query->with('group');
		$query->with('config');
		/** @var PackageEntity $entity */
		$entity = \App::$domain->package->package->oneById('yii2rails/yii2-extension', $query);
		$this->tester->assertIsEntity($entity, PackageEntity::class);
		$this->tester->assertIsEntity($entity->group, GroupEntity::class);
		$this->tester->assertIsEntity($entity->config, ConfigEntity::class);
	}
	
}
