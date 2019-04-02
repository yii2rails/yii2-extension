<?php

namespace yii2rails\extension\common\helpers\time;

use yii2rails\extension\web\enums\HttpHeaderEnum;

interface TimeDriverInterface {
	
	public function get();
	public function set(string $timeZone);
	
}
