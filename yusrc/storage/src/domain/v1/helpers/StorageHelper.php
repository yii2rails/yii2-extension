<?php

namespace yubundle\storage\domain\v1\helpers;

use App;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\enums\RelationClassTypeEnum;
use yii2rails\domain\enums\RelationEnum;
use yubundle\storage\domain\v1\entities\FileEntity;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\storage\domain\v1\entities\ServiceThumbEntity;

class StorageHelper
{

    public static function generateThumbsUrls(FileEntity $fileEntity) : array {
        $thumbs = [];
        $thumbArray = ArrayHelper::getValue($fileEntity, 'service.thumbs', []);
        if($thumbArray) {
            foreach ($thumbArray as $thumbEntity) {
                $dirName = StorageHelper::forgeThumbDirName($thumbEntity);
                $url = str_replace($fileEntity->hash, $dirName . SL . $fileEntity->hash, $fileEntity->url->constant);
                $thumbKey = $thumbEntity->width . 'x' . $thumbEntity->height;
                $thumbs[$thumbKey] = $url;
            }
        }
        return $thumbs;
    }

    public static function generateSchemaForAttachments(string $foreignId, $isOne = false, $field = 'id', $foreignField = 'entity_id') : array {
        if($isOne) {
            return [
                'type' => RelationEnum::ONE,
                'field' => $field,
                'foreign' => [
                    'classType' => RelationClassTypeEnum::SERVICE,
                    'id' => $foreignId,
                    'field' => $foreignField,
                ],
            ];
        } else {
            return [
                'type' => RelationEnum::MANY,
                'field' => $field,
                'foreign' => [
                    'classType' => RelationClassTypeEnum::SERVICE,
                    'id' => $foreignId,
                    'field' => $foreignField,
                ],
            ];
        }
    }

    public static function getWebPath() : string {
        $webRootAlias = EnvService::getServer('static.publicPath');
        if(empty($webRootAlias)) {
            $webRootAlias = '@webroot';
        }
        $webRoot = \Yii::getAlias($webRootAlias);
        $webRootStorage = $webRoot . SL . 'storage';
        return $webRootStorage;
    }

    public static function getWebUrl() : string {
        $webRoot = EnvService::getServer('storage.resourceHost');
        $webRootStorage = $webRoot . SL . 'storage';
        return $webRootStorage;
    }

    public static function generateFileName(string $relativePath) : string {
        $webRootStorage = self::getWebPath();
        return $webRootStorage . SL . $relativePath;
    }

    public static function generateFileUrl(string $relativePath) : string {
        $webRootStorage = self::getWebUrl();
        return $webRootStorage . SL . $relativePath;
    }

    public static function generateRelativePath(string $serviceCode, $entityId, string $fileName) : string {
        $serviceEntity = \App::$domain->storage->service->oneByCode($serviceCode);
        $relativePath = $serviceEntity->path . SL . $entityId . SL . $fileName;
        return $relativePath;
    }

    public static function forgeResourceUrl(string $path) : string {
        $host = EnvService::getServer('storage.resourceHost');
        $url = trim($host, SL);
        if(!empty($path)) {
            $url = $url . SL . $path;
        }
        return $url;
    }

    public static function forgeApiUrl(string $path) : string {
        $host = EnvService::getServer('storage.apiHost');
        $url = trim($host, SL);
        if(!empty($path)) {
            $url = $url . SL . $path;
        }
        return $url;
    }

    public static function parseFilePath(string $filePath) : array {
        $info['thumbSize'] = self::parseThumbDirName($filePath);
        $info['dir'] = dirname($filePath);
        $info['profileDir'] = preg_replace('/(\/thumb\d+x\d+)/i', '', $info['dir']);
        //$info['profileDir'] = str_replace('storage/', '', $info['profileDir']);
        $info['originalFilePath'] = $filePath;
        $info['fileName'] = FileHelper::fileNameOnly($filePath);
        $info['extension'] = FileHelper::fileExt($filePath);

        preg_match('/\/(\d+)($|\/)/i', $info['profileDir'], $matches);
        if(!empty($matches[1])) {
            $info['entityId'] = intval($matches[1]);
        }

        return $info;
    }

    public static function getEntityIdByPath(string  $filePath) {
        $dir = dirname($filePath);
        $profileDir = preg_replace('/(\/thumb\d+x\d+)/i', '', $dir);
        preg_match('/\/(\d+)($|\/)/i', $profileDir, $matches);
        $entityId = null;
        if(!empty($matches[1])) {
            $entityId = intval($matches[1]);
        }
        return $entityId;
    }

    public static function removeThumbDirName(string $filePath) : string {
        $filePath = preg_replace('/(\/thumb\d+x\d+)/i', '', $filePath);
        return $filePath;
    }

    public static function parseThumbDirName(string $filePath) : array {
        $dir = dirname($filePath);
        $isThumb = preg_match('/(\/thumb)(\d+)x(\d+)$/i', $dir, $m);
        if(!$isThumb) {
            return [];
        }
        $size['width'] = $m[2];
        $size['height'] = $m[3];
        return $size;
    }

    public static function forgeThumbDirName(ServiceThumbEntity $thumbEntity) {
        return 'thumb' . $thumbEntity->width . 'x' . $thumbEntity->height;
    }

    /**
     * @param $name
     * @return null|UploadedFile
     */
    public static function forge($name) {
        $uploadedFile = UploadedFile::getInstanceByName($name);
        return $uploadedFile;
    }

    /**
     * @param $files
     * @param $name
     * @return UploadedFile
     * @deprecated use self::forge()
     */
    public static function forgeUploaded($files, $name) : UploadedFile {
        $uploadedFile = UploadedFile::getInstanceByName($name);
        return $uploadedFile;
    }

    public static function forgeUploadedCollection($files) {
        $collection = [];
        $names = array_keys($files);
        foreach ($names as $name) {
            $uploadedFile = UploadedFile::getInstanceByName($name);
            $collection[$name] = $uploadedFile;
        }
        return $collection;
    }

    public static function forgeFileEntity(string $fileName) : FileEntity {
        $fileEntity = new FileEntity;
        $fileEntity->extension = FileHelper::fileExt($fileName);
        $fileEntity->name = FileHelper::fileRemoveExt(basename($fileName));
        $fileEntity->hash = hash_file('crc32b', $fileName);
        $fileEntity->size = filesize($fileName);
        $fileEntity->service_id = 1;
        $fileEntity->service = \App::$domain->storage->service->oneById($fileEntity->service_id);
        return $fileEntity;
    }

}