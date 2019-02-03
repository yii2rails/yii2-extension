<?php

namespace yii2rails\extension\scenario\base;

use yii2rails\extension\scenario\collections\ScenarioCollection;

abstract class BaseGroupScenario extends BaseScenario {

    public $filters = [];

    public function run() {
        if(empty($this->filters)) {
            return;
        }
        $config = $this->getData();

        $filterCollection = new ScenarioCollection($this->filters);
        $config = $filterCollection->runAll($config);

        $this->setData($config);
    }
	
}
