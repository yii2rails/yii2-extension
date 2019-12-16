<?php

namespace yubundle\storage\domain\v1\repositories\file;

use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\entities\StorageEntity;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\storage\domain\v1\interfaces\repositories\ServerInterface;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\flySystem\repositories\base\BaseFlyRepository;
use yubundle\storage\domain\v1\interfaces\repositories\StorageInterface;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii2rails\extension\common\helpers\TempHelper;
use yii2rails\extension\yii\helpers\FileHelper;

/**
 * Class ServerRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\file
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class ServerRepository extends BaseFlyRepository implements ServerInterface {

	protected $schemaClass = true;
    public $profile = 'storage';
    private $profilePath;

    public function init()
    {
        $profileConfig = EnvService::getServer('static.profiles.' . $this->profile);
        $this->profilePath = $profileConfig['path'];
        parent::init();
    }

    public function insert(FileEntity $fileEntity, string $tempFile, $encoding) {
        $content = FileHelper::load($tempFile);
        if ($encoding == 'base64') {
            $content = base64_decode($content);
        }
        $this->writeFile($fileEntity->file_path, $content);
    }

    /*public function saveFile($fileName) {
        $fileEntity = StorageHelper::forgeFileEntity($fileName);
        $content = FileHelper::load($fileName);
        $this->writeFile($fileEntity->file_path, $content);
        return $fileEntity;
    }*/

    /*public function allByOwner($ownerId): array {
        $dir = $this->profilePath . SL . $ownerId;
        $fileList = $this->fileList($dir);
        $collection = [];
        foreach ($fileList as $fileInfo) {
            $collection[] = StorageHelper::forgeStorageEntityFromFileInfo($fileInfo);
        }
        return $collection;
    }

    public function oneById($id): StorageEntity {
        $id1 = str_replace('_', SL , $id);
        $fileInfo = $this->getMetadata($this->profilePath . SL . $id1);
        $storageEntity = new StorageEntity;
        $storageEntity->id = $id;
        $storageEntity->size = $fileInfo['size'];
        $storageEntity->dir = $this->profilePath . SL . $storageEntity->owner_id;
        return $storageEntity;
    }*/

    /*public function delete(BaseEntity $entity) {
        d($entity->dir . SL . $entity->hash . DOT . $entity->ext);
    }*/

    public function delete(BaseEntity $entity) {
        $this->removeFile($entity->file_path);
    }

    public function move(BaseEntity $entity, BaseEntity $toEntity) {
        $this->moveFile($entity->file_path, $toEntity->file_path);
    }

    /*public function deleteById($id) {
        $storageEntity = $this->oneById($id);
        $this->removeFile($storageEntity->getFilePath());
    }*/

}
