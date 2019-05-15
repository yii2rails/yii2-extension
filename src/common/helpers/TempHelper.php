<?php

namespace yii2rails\extension\common\helpers;

use Yii;
use yii\web\UploadedFile;
use yii2rails\extension\yii\helpers\FileHelper;

class TempHelper {
	
	const TEMP_ALIAS = '@common/runtime/temp/upload';

	private static $_directoryName;
	
	public static function remove($name) {
		$fileName = self::fullName($name);
		FileHelper::remove($fileName);
	}
	
	public static function save($name, $content) {
		$fileName = self::fullName($name);
		FileHelper::save($fileName, $content);
	}
	
	public static function fullName($name) {
		$fileName = self::basePath($name);
		self::createDirectoryForFile($fileName);
		return $fileName;
	}
	
	public static function createDirectoryForFile($fileName) {
		$directory = FileHelper::up($fileName);
		$isCreated = FileHelper::createDirectory($directory);
		if($isCreated) {
            register_shutdown_function(self::getCleanClosure());
        }
	}
	
	public static function copyUploadedToTemp(UploadedFile $uploaded, $fileEncoding) {
	    if ($fileEncoding == 'base64') {
            $fileBase64Content = file_get_contents($uploaded->tempName);
            $fileContent = base64_decode($fileBase64Content);
            file_put_contents ($uploaded->tempName, $fileContent);
        }
		$tempFile = self::fullName($uploaded->name);
		$uploaded->saveAs($tempFile);
		return $tempFile;
	}
	
	public static function basePath($path = null) {
	    if(!isset(self::$_directoryName)) {
            self::$_directoryName = Yii::getAlias(self::TEMP_ALIAS) . DS . YII_BEGIN_TIME;
        }
        $basePath  = self::$_directoryName;
		if($path) {
			$basePath .= DS . $path;
		}
		$basePath = FileHelper::normalizePath($basePath);
		return $basePath;
	}

	private static function getCleanClosure() {
	    return function () {
            FileHelper::removeDirectory(self::basePath());
        };
    }
}
