<?php

namespace yii2rails\extension\common\helpers\time;

use yii2rails\extension\web\enums\HttpHeaderEnum;

class TimeMockDriver implements TimeDriverInterface {
	
	public function get() {
		return null;
	}
	
	public function set(string $timeZone) {

	}
	
}
