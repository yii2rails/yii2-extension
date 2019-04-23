<?php

namespace yii2rails\extension\telegram\helpers;

use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class MenuHelper {
	
	public static function createKeyboard($keys) {
		$keyboardArray = MenuHelper::splitButtons($keys);
		$keyboard = new ReplyKeyboardMarkup($keyboardArray, true, true);
		return $keyboard;
	}
	
	public static function splitButtons($keys) {
		$current = 0;
		$keyboardArray = [];
		foreach($keys as $k => $text) {
			$keyboardArray[$current][] = $text;
			if(($k + 1) % 3 == 0) {
				$current++;
			}
		}
		return $keyboardArray;
	}
	
}
