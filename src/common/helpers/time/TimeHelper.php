<?php

namespace yii2rails\extension\common\helpers\time;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\strategies\join\JoinStrategy;
use yii2rails\extension\web\enums\HttpHeaderEnum;

class TimeHelper {
	
	const ENV_KEY = 'client.defaultTimeZone';
	
	protected static $driver;
	
	public static function getTimeZone() {
		$driver = self::getDriver();
		$timeZone = $driver->get();
		if(empty($timeZone)) {
			$timeZone = self::getDefaultTimeZone();
		}
		return $timeZone;
	}
	
	public static function setTimeZone(string $timeZone) {
		$driver = self::getDriver();
		$timeZone = $driver->set($timeZone);
	}
	
	private static function getDefaultTimeZone() : string {
		return EnvService::get(self::ENV_KEY, \Yii::$app->timeZone);
	}
	
	private static function getDriver() : TimeDriverInterface {
		if(empty(self::$driver)) {
			if(APP == API) {
				self::$driver = new TimeHeaderDriver;
            } elseif(APP == CONSOLE) {
                self::$driver = new TimeMockDriver;
			} else {
				self::$driver = new TimeCookieDriver;
			}
		}
		return self::$driver;
	}
	
}
