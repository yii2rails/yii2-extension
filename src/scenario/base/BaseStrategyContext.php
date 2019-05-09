<?php

namespace yii2rails\extension\scenario\base;

use yii\base\BaseObject;
use yii2rails\extension\common\helpers\ClassHelper;
use yii2rails\extension\common\helpers\InstanceHelper;

/**
 * Class BaseStrategyContext
 *
 * @package yii2rails\extension\scenario\base
 *
 * @property-read Object $strategyInstance
 */
abstract class BaseStrategyContext extends BaseObject {
	
	private $strategyInstance;
	
	public function getStrategyInstance() {
		return $this->strategyInstance;
	}
	
	public function setStrategyInstance($strategyInstance) {
		$this->strategyInstance = $strategyInstance;
	}
	
	public function setStrategyDefinition($strategyDefinition) {
		$strategyInstance = $this->forgeStrategyInstance($strategyDefinition);
		$this->setStrategyInstance($strategyInstance);
	}
	
	public function forgeStrategyInstance($strategyDefinition) {
		$strategyInstance = InstanceHelper::create($strategyDefinition, []);
		return $strategyInstance;
	}
	
}
