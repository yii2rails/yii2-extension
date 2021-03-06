<?php

namespace yii2rails\extension\git\domain\repositories\file;

use yii2rails\extension\arrayTools\repositories\base\BaseActiveArrayRepository;
use yii2rails\extension\git\domain\helpers\GitConfigHelper;
use yii2rails\extension\git\domain\interfaces\repositories\GitInterface;
use yii2rails\extension\git\domain\entities\GitEntity;
use yii2rails\extension\package\domain\entities\PackageEntity;

/**
 * Class GitRepository
 * 
 * @package yii2rails\extension\git\domain\repositories\file
 * 
 * @property-read \yii2rails\extension\git\domain\Domain $domain
 */
class GitRepository extends BaseActiveArrayRepository implements GitInterface {

	protected $schemaClass = true;
	
	public function oneByDir($dir) {
		$entity = new GitEntity;
		$entity->dir = $dir;
		$arr = GitConfigHelper::loadIni($dir);
		$entity->branches = $arr['branch'];
		$entity->remotes = $arr['remote'];
		$entity->refs = GitConfigHelper::getRefs($dir);
		$entity->tags = GitConfigHelper::getTagsByRefs($entity->refs);
		return $entity;
	}
	
	protected function getCollection() {
		/** @var PackageEntity[] $packageCollection */
		$packageCollection = \App::$domain->package->package->all();
		$collection = [];
		foreach($packageCollection as $packageEntity) {
			$collection[] = $this->oneByDir($packageEntity->dir);
		}
		return $collection;
	}
	
}
