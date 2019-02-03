<?php

namespace yii2rails\extension\arrayTools\filters\iterator;

use yii2rails\extension\scenario\base\BaseScenario;
use yii2rails\domain\data\Query;

class Offset extends BaseScenario {

	public $query;
	
	public function run() {
		$collection = $this->getData();
		$collection = $this->filterWhere($collection, $this->query);
		$this->setData($collection);
	}
	
	protected function filterWhere($collection, Query $query) {
		$offset = $query->getParam('offset', 0);
		$limit = $query->getParam('limit', null);
		if(empty($offset) && empty($limit)) {
			return $collection;
		}
		//prr([$offset, $limit],1,1);
		$collection = array_slice($collection, $offset, $limit);
		return $collection;
	}
}
