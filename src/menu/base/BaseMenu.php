<?php

namespace yii2rails\extension\menu\base;

use Yii;

class BaseMenu {
	
	protected function filter($items) {
		foreach($items as &$item) {
			$item['active'] = trim(Yii::$app->request->url, SL) == $item['url'];
		}
		return $items;
	}
	
}
