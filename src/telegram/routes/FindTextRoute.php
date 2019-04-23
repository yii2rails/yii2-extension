<?php

namespace yii2rails\extension\telegram\routes;

use TelegramBot\Api\Types\Message;

class FindTextRoute extends BaseRoute {

	public function isMatch(Message $message) {
		$text = $message->getText();
		return $this->isMatchText($text, $this->exp);
	}
	
	private function isMatchText($text, $pattern) {
		$text = strtolower($text);
		$pattern = strtolower($pattern);
		return mb_stripos($text, $pattern) !== false;
	}
}
