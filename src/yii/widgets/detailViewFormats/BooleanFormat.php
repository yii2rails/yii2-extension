<?php

namespace yii2rails\extension\yii\widgets\detailViewFormats;

use yii2rails\extension\yii\helpers\Html;

class BooleanFormat {
	
	public function run($value) {
		return Html::renderBoolean($value);
	}

}
