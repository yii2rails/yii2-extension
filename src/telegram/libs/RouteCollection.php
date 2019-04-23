<?php

namespace yii2rails\extension\telegram\libs;

use yii2rails\extension\arrayTools\base\BaseCollection;

class RouteCollection extends BaseCollection {
	
	public function add($route, $handlerDefinition) {
	    $this->offsetSet($route, $handlerDefinition);
    }

    public function load($routes) {
        $this->loadItems($routes);
    }

}
