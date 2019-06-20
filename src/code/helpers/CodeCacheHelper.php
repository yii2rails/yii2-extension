<?php

namespace yii2rails\extension\code\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\extension\code\entities\TokenEntity;
use yii2rails\extension\code\helpers\parser\TokenCollectionHelper;
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

    private static function implodeCode($files) {
        $res = '';
        foreach($files as $code) {
            $code = self::trimCode($code);
            $res .= '<?php ' . $code . ' ?>';
        }
        return $res;
    }

    private static function isMatchArray($pathesArray, $string) {
        foreach ($pathesArray as $path) {
            if(strpos($string, $path) === 0) {
                return true;
            }
	    }
        return false;
    }

    private static function loadClassesCode($classes) {
        $files = [];
        foreach($classes as $class) {
            $code = self::loadClassCode($class);
            if(strpos($code, '__DIR__') === false) {
                $files[] = $code;
            }
        }
        return $files;
    }

    public static function filter($classes, $needle, $isInvertMatch = false) {
	    $fiteredClasses = [];
        foreach($classes as $class) {
            $isMatch = self::isMatchArray($needle, $class);
            $isMatch = $isInvertMatch ? !$isMatch : $isMatch;
            if($isMatch) {
                $fiteredClasses[] = $class;
            }
        }
        return $fiteredClasses;
    }

	public static function saveClassesCache($classes) {
	    $include = [
            /*'api\\',
            'backend\\',
            'common\\',
            'console\\',
            'domain\\',
            'frontend\\',*/

            'yii\\',

            'yii2lab',
            'yii2module',
            'yii2rails',
            'yii2tech',
            //'yii2mod',
            'yii2bundle',
            'yii2tool',
            'yubundle',
        ];
        $exclude = [
            'yii\\helpers',
            'yii2rails\\app\\domain\\helpers\\Env',
            'yii2rails\\domain\\interfaces\\repositories',
            'yii2rails\\domain\\interfaces\\services',
            'yii2rails\\extension\\develop\\helpers\\Benchmark',
            'yii2rails\\extension\\registry\\base\\BaseRegistry',
            'yii2rails\\extension\\registry\\helpers\\Registry',
            'yii2rails\\extension\\registry\\interfaces\\RegistryInterface',
        ];

        $classes = self::filter($classes, $include);
        $classes = self::filter($classes, $exclude, true);
        $files = self::loadClassesCode($classes);
        $res = self::implodeCode($files);
        $codeTokenCollection = TokenHelper::codeToCollection($res);
        $codeTokenCollection = TokenCollectionHelper::removeComment($codeTokenCollection);
        $res = TokenHelper::collectionToCode($codeTokenCollection);
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
