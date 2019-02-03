<?php

namespace yii2rails\extension\web\traits;

use yii2rails\extension\web\helpers\ControllerHelper;

trait ActionEventTrait
{
	
	protected function callActionTrigger($eventName, $data = null) {
		$event = ControllerHelper::runActionTrigger($this, $eventName, $data);
		return $event->result;
	}

}
