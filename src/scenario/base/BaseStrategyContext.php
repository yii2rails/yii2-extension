<?php

namespace yii2lab\extension\scenario\base;

use yii\base\BaseObject;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\extension\common\helpers\InstanceHelper;

/**
 * Class BaseStrategyContext
 *
 * @package yii2lab\extension\scenario\base
 *
 * @property-read Object $strategyInstance
 */
abstract class BaseStrategyContext extends BaseObject {
	
	private $strategyInstance;
	
	public function getStrategyInstance() {
		return $this->strategyInstance;
	}
	
	public function setStrategyInstance(Object $strategyInstance) {
		$this->strategyInstance = $strategyInstance;
	}
	
	public function setStrategyDefinition($strategyDefinition) {
		$strategyInstance = InstanceHelper::create($strategyDefinition, []);
		$this->setStrategyInstance($strategyInstance);
	}
	
}
