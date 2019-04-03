<?php

namespace yii2rails\extension\common\helpers\time;

use yii2rails\extension\web\enums\HttpHeaderEnum;

class TimeCookieDriver implements TimeDriverInterface {
	
	public function get() {
		return \Yii::$app->request->cookies->get(HttpHeaderEnum::TIME_ZONE);
	}
	
	public function set(string $timeZone) {
		\Yii::$app->response->cookies[HttpHeaderEnum::TIME_ZONE] = $timeZone;
	}
	
}
