<?php

namespace yii2rails\extension\validator\helpers;

use Exception;
use yii2rails\extension\enum\enums\TimeEnum;

class IinDateHelper {
	
	public static function parseCentury($value) {
		$century = substr($value, 6, 1);
		IinDateHelper::validateCentury($century);
		return $century;
	}
	
	public static function parseDate($value) {
		$age = self::getAge($value);
		$smallYear = substr($value, 0, 2);
		$date['year'] = $age . $smallYear;
		$date['month'] = substr($value, 2, 2);
		$date['day'] = substr($value, 4, 2);
		if(!self::validateDate($date)) {
			throw new Exception();
		}
		self::getOld($date);
		return $date;
	}
	
	private static function validateCentury($century) {
		$maxCentury = self::getMaxCentury();
		if ($century < 0 || $century > $maxCentury) {
			throw new Exception();
		}
	}
	
	private static function getOld($date) {
		$birthDateString = self::dateToString($date);
		$nowDateString = self::getNowDateAsString();
		$diffSec = self::dateStringToTimestamp($nowDateString) - self::dateStringToTimestamp($birthDateString);
		$yearCount = floor($diffSec / TimeEnum::SECOND_PER_YEAR);
		if ($yearCount <= 0) {
			throw new Exception();
		}
		return $yearCount;
	}
	
	private static function validateDate($date) {
		return checkdate ( $date['month'] , $date['day'] , $date['year'] );
	}

    private static function getAge($value) {
        $century = self::parseCentury($value);
        $residue = $century % 2;
        if($residue == 0) {
            $century --;
        }

        $centuryDiv = floor($century / 2);

        return $centuryDiv + 18;
    }
	
	private static function dateStringToTimestamp($dateString) {
		list($year, $month, $day) = explode('-', $dateString);
		return
			$year * TimeEnum::SECOND_PER_YEAR +
			$month * TimeEnum::SECOND_PER_MONTH +
			$day * TimeEnum::SECOND_PER_DAY;
	}
	
	private static function getNowDateAsString() {
		return date('Y-m-d', time());
	}
	
	private static function dateToString($part) {
		return "{$part['year']}-{$part['month']}-{$part['day']}";
	}
	
	private static function getMaxCentury() {
		$nowYear = date('Y');
		$nowAge = substr($nowYear, 0, 2);
		return ($nowAge - 18 + 1) * 2;
	}
	
}
