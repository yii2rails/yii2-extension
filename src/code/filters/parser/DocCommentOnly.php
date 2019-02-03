<?php

namespace yii2rails\extension\code\filters\parser;

use yii2rails\extension\code\entities\TokenEntity;

class DocCommentOnly extends BaseRemove {
	
	public function run() {
		/** @var TokenEntity[] $collection */
		$collection = $this->getData();
		foreach($collection as $k => &$entity) {
			if($this->isIgnore($entity)) {
				unset($collection[$k]);
			}
		}
		$this->setData($collection);
	}
	
	private function isIgnore(TokenEntity $entity) {
		return $entity->type != T_DOC_COMMENT;
	}
}
