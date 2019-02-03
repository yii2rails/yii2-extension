<?php

namespace yii2rails\extension\arrayTools\repositories\base;

use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\arrayTools\traits\ArrayModifyTrait;
use yii2rails\extension\arrayTools\traits\ArrayReadTrait;

abstract class BaseActiveArrayRepository extends BaseRepository implements CrudInterface {
	
	use ArrayReadTrait;
	use ArrayModifyTrait;
	
	private $collection = [];
	
	protected function setCollection(Array $collection) {
		$this->collection = $collection;
	}
	
	protected function getCollection() {
		return $this->collection;
	}
}
