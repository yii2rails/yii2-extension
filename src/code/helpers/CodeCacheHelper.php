<?php

namespace yii2rails\extension\code\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\extension\code\entities\TokenEntity;
use yii2rails\extension\code\helpers\parser\TokenHelper;
use yii2rails\extension\develop\helpers\Benchmark;
use yii2rails\extension\store\StoreFile;
use yii2rails\extension\yii\helpers\FileHelper;

class CodeCacheHelper
{
	
	const CLASS_DEFINITION_ALIAS = 'common/runtime/cache/app/classes_code.php';
	const CLASS_DEFINED_ALIAS = 'common/data/code/classes.json';
    const NAMESPACE_EXP = '[a-z0-9_\\\\]+';

    private static $excludeClasses = [
		'yii\BaseYii',
	];
	
	public static function loadClassesCache() {
		Benchmark::begin('load_classes_cache');
		$fileName = ROOT_DIR . DS . self::CLASS_DEFINITION_ALIAS;
		@include_once $fileName;
		Benchmark::end('load_classes_cache');
	}
	
	private static function isExcludeClassName($className) {
		return in_array($className, self::$excludeClasses);
	}
	
	private static function evalCode($className, $code) {
		if(self::isExcludeClassName($className)) {
			return;
		}
		if(class_exists($className) || trait_exists($className) || interface_exists($className)) {
			return;
		}
		eval($code);
	}

    private static function trimCode($code) {
        $code = preg_replace([
            '#^(\<\?php)#',
            '#^(\<\?)#',
            '#(\?\>)$#',
        ], '', $code);
        return $code;
    }

	private static function hh($classes) {
        $files = [];
        foreach($classes as $class) {
            if(!self::isExcludeClassName($class)) {
                $code = self::loadClassCode($class);
                if(preg_match('/namespace\s+('.self::NAMESPACE_EXP.');/i', $code, $matches)) {
                    $namespace = $matches[1];
                    $code = preg_replace('/(namespace\s+'.self::NAMESPACE_EXP.';)/i', '', $code);
                } else {
                    $namespace = '\\';
                }
                //if(strpos($namespace, 'yii2lab\navigation\domain') === 0) {
                    $files[$namespace][] = $code;
                //}
            }
        }
        return $files;
    }

    private static function searchTag($codeTokenCollection, $needle, $start = 0) {
        for($i = $start; $i < count($codeTokenCollection); $i++) {
            $phpToken = $codeTokenCollection[$i];
            if($phpToken->value == $needle) {
                return $i;
            }
        }
        return null;
    }

    private static function searchUses($codeTokenCollection) {
	    $start = null;
        $useArr = [];
	    /** @var TokenEntity[] $codeTokenCollection */
        foreach($codeTokenCollection as $index => $tag) {
            if($start === null) {
                if($tag->type == T_USE) {
                    $start = $index;
                }
            } else {
                if($tag->value == '(') {
                    $start = null;
                } elseif($tag->value == ';') {
                    $useArr[] = [
                        'start' => $start,
                        'end' => $index,
                    ];
                    $start = null;
                }
            }
        }
        return $useArr;
    }

    private static function rewriteToSpaces($codeTokenCollection, $start, $end) {
        for ($i = $start; $i <= $end; $i++) {
            $spaceEntity = new TokenEntity([
                'type' => '382',
                'value' => ' ',
                'line' => '5',
                'type_name' => 'T_WHITESPACE',
            ]);
            $codeTokenCollection[$i] = $spaceEntity;
	    }
	    return $codeTokenCollection;
    }

    private static function gg($files) {
        $res = '';
        foreach($files as $namespace => $codeArray) {
            $namespaceCode = '';
            foreach($codeArray as $code) {
                $codeTokenCollection = TokenHelper::codeToCollection($code);
                $uses = self::searchUses($codeTokenCollection);

                $useClasses = [];
                $uses = array_reverse($uses);
                foreach($uses as $use) {
                    $lastIndex = $use['end'] - 1;
                    $className = $codeTokenCollection[$lastIndex]->value;
                    $len = $use['end'] - $use['start'];
                    $arr = array_slice($codeTokenCollection, $use['start'], $len);
                    foreach ($arr as $tt => $item) {
                        if($item->type != T_STRING && $item->type != T_NS_SEPARATOR) {
                            unset($arr[$tt]);
                        }
                    }
                    $useClasses[$className] = trim(TokenHelper::collectionToCode($arr), '\\');
                    $codeTokenCollection = self::rewriteToSpaces($codeTokenCollection, $use['start'], $use['end']);
                }
                foreach($codeTokenCollection as $ii => $tokenEntity) {
                    $prevValue = ArrayHelper::getValue($codeTokenCollection, ($ii - 1) . '.value');
                    if($prevValue != '\\' && $tokenEntity->type == T_STRING && !empty($useClasses[$tokenEntity->value])) {
                        $tokenEntity->value = '\\' . $useClasses[$tokenEntity->value];
                    }
                }
                $code = TokenHelper::collectionToCode($codeTokenCollection);
                $code = self::trimCode($code);
                $namespaceCode .= PHP_EOL . PHP_EOL . $code . PHP_EOL . PHP_EOL;
            }
            if($namespace == '\\') {
                $res .= PHP_EOL . PHP_EOL . $namespaceCode . PHP_EOL . PHP_EOL;
            } else {
                $res .= PHP_EOL . PHP_EOL . 'namespace ' . $namespace . ' {' . PHP_EOL . $namespaceCode .  PHP_EOL . '}' . PHP_EOL . PHP_EOL;
            }
        }
        return $res;
    }

	public static function saveClassesCache($classes) {
        $files = self::hh($classes);
        $res = self::gg($files);
        $res = '<?php' . $res;
		$fileName = self::getFileName();
        FileHelper::save($fileName, $res);
	}

	public static function getFileName() {
	    return ROOT_DIR . DS . self::CLASS_DEFINITION_ALIAS;
    }

	private static function loadClassCode($class) {
		$file = ClassHelper::classNameToFileName($class);
		$itemCode = FileHelper::load($file . DOT . 'php');
		return $itemCode;
	}
	
}
