<?php

namespace yubundle\storage\domain\v1\services;

use App;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\entities\ServiceEntity;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\storage\domain\v1\interfaces\services\StaticInterface;
use yii2rails\domain\services\base\BaseService;

/**
 * Class StaticService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\StaticInterface $repository
 */
class StaticService extends BaseService implements StaticInterface {

    public function getFileEntityByFileHash(string $filePath) : FileEntity {
        $dir = dirname($filePath);
        $entityId = StorageHelper::getEntityIdByPath($filePath);
        $hash = FileHelper::fileNameOnly($filePath);
        $serviceEntity = $this->domain->repositories->service->oneByDir($dir);
        $conditionCollection = [
            'service_id' => $serviceEntity->id,
            'hash' => $hash,
            'entity_id' => $entityId,
        ];
        $fileEntity = $this->getFileEntityByCondition($conditionCollection);
        return $fileEntity;
    }

    public function getFileEntityByFilePath(string $filePath) : FileEntity {
        $dir = dirname($filePath);
        $entityId = StorageHelper::getEntityIdByPath($filePath);
        $name = FileHelper::fileNameOnly($filePath);
        $serviceEntity = $this->domain->repositories->service->oneByDir($dir);
        $fileEntity = $this->getFileEntity($serviceEntity->id, $name, $entityId);
        return $fileEntity;
    }

    private function getFileEntity(int $serviceId, string $name, int $entityId) : FileEntity {
        $query = new Query;
        $query->andWhere([
            'service_id' => $serviceId,
            'name' => $name,
            'entity_id' => $entityId,
        ]);
        $query->with('service');
        $query->with('ext');
        return App::$domain->storage->file->one($query);
    }

    private function getFileEntityByCondition ($conditionCollection) : FileEntity {
        $query = new Query;
        $query->andWhere($conditionCollection);
        $query->with('service');
        $query->with('ext');
        return App::$domain->storage->file->one($query);
    }

}