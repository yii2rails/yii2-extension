<?php

namespace yii2rails\extension\common\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\yii\helpers\FileHelper;

class Helper {

    public static function microtimeId($length = 14) {
        $timeArray = explode(DOT, microtime(true));
        $time = implode(EMP, $timeArray);
        $len = mb_strlen($time);
        if($len > 14) {

        }
        if($length < $len) {
            $mock = str_repeat('0', $length - $len);
            $time .= $mock;
        }
        if($length > 14) {
            $diff = $length - 14;
            $min = str_repeat('0', $diff);
            $max = str_repeat('9', $diff);
            $rand = mt_rand($min, $max);
            $time .= $rand;
        }
        return $time;
    }

    public static function includeConfig(string $file, array $mergeConfig = []) : array {
        $parentConfig = include($file);
        return ArrayHelper::merge($parentConfig, $mergeConfig);
    }

	public static function list2tree($secureAttributes) {
		$tree = [];
		foreach($secureAttributes as $attribute) {
			ArrayHelper::setValue($tree, $attribute, true);
		}
		return $tree;
	}
	
	public static function parseParams($path, $delimiter, $subDelimiter) {
		$isHasParams = strpos($path, $delimiter);
		if(!$isHasParams) {
			return null;
		}
		$params = [];
		$segments = explode($delimiter, $path);
		foreach($segments as $segment) {
			$s = explode($subDelimiter, $segment);
			if(count($s) > 1) {
				$params[$s[0]] = $s[1];
			} else {
				$params[] = $s[0];
			}
		}
		return $params;
	}
	
	public static function getNotEmptyValue() {
		$args = func_get_args();
		foreach($args as $arg) {
			if(!empty($arg)) {
				return $arg;
			}
		}
		return null;
	}
	
    public static function loadData($name, $key = null) {
        $file = COMMON_DATA_DIR . DS . $name . '.php';
        $data = FileHelper::loadData($file, $key, []);
        return $data;
    }

	public static function isEnabledComponent($config) {
		if(!is_array($config)) {
			return $config;
		}
		$isEnabled = !isset($config['isEnabled']) || !empty($config['isEnabled']);
		unset($config['isEnabled']);
		if(!$isEnabled) {
			return null;
		}
		return $config;
	}
	
	public static function assignAttributesForList($configList, $attributes = null) {
		$configList = ClassHelper::normalizeComponentListConfig($configList);
		foreach($configList as &$item) {
			foreach($attributes as $attributeName => $attributeValue) {
				$item[$attributeName] = $attributeValue;
			}
		}
		return $configList;
	}

	static function getBundlePath($path) {
		if(empty($path)) {
			return false;
		}
		$alias = FileHelper::normalizeAlias($path);
		$dir = Yii::getAlias($alias);
		if(!is_dir($dir)) {
			return false;
		}
		return $alias;
	}
	
	static function getCurrentDbDriver() {
        $driver = EnvService::getConnection('main.driver');
		return  $driver;
	}

	static function getDbConfig($name = null, $isEnvTest = YII_ENV_TEST)
	{
		$configName = $isEnvTest ? 'test' : 'main';
		$config = EnvService::get("db.$configName");
		if($name) {
			return $config[$name];
		} else {
			return $config;
		}
	}

	/*static function strToArray($value) {
		$value = trim($value, '{}');
		$value = explode(',', $value);
		return $value;
	}*/
	
	static function timeForApi($value, $default = null, $mask = 'Y-m-d\TH:i:s\Z') {
		if(APP != API) {
			return $value;
		}
		if(empty($value)) {
			return $default;
		}
		if(is_numeric($value)) {
			$value = date('Y-m-d H:i:s', $value);
		}
		$datetime = new \DateTime($value);
		$value = $datetime->format($mask);
		return $value;
	}

}