<?php

namespace yii2rails\extension\code\render;

use yii2rails\extension\code\entities\ClassEntity;

/**
 * Class ConstantRender
 *
 * @package yii2rails\extension\code\render
 *
 * @property ClassEntity $entity
 */
class ConstantRender extends BaseRender
{
	
	public function run() {
		if($this->entity->constants == null) {
			return EMP;
		}
		$code = PHP_EOL;
		$code .= $this->renderItems($this->entity->constants);
		return $code;
	}
	
	protected function renderItem($constantEntity) {
		return TAB . 'const ' . $constantEntity->name . ' = ' . $constantEntity->value . ';' . PHP_EOL;
	}
}
