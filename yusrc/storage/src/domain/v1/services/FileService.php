<?php

namespace yubundle\storage\domain\v1\services;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\interfaces\services\FileInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class FileService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileInterface $repository
 */
class FileService extends BaseActiveService implements FileInterface {

    protected function prepareQuery(Query $query = null)
    {
        $query = parent::prepareQuery($query);
        $query->with('service');
        return $query;
    }

    public function oneByServiceIdAndEntityId(int $serviceId, int $entityId) : FileEntity {
        $query = new Query;
        $query->andWhere([
            'service_id' => $serviceId,
            'entity_id' => $entityId,
        ]);
        return $this->one($query);
    }

    public function moveAll(array $fileIds, string $toServiceCode, int $toEntityId) {
        if(empty($fileIds)) {
            return;
        }
        foreach ($fileIds as $fileId) {
            \App::$domain->storage->file->move($fileId, $toServiceCode, $toEntityId);
        }
    }

    public function move(int $id, string $toServiceCode, int $toEntityId) {
        $query = new Query;
        $query->with('service');

        /** @var FileEntity $fileEntity */
        $fileEntity = \App::$domain->storage->file->oneById($id, $query);

        $serviceEntity = \App::$domain->storage->service->oneByCode($toServiceCode);

        //TODO: обработка случая, когда такой файл уже есть
        try {
            \App::$domain->storage->file->updateById($id, [
                'service_id' => $serviceEntity->id,
                'entity_id' => $toEntityId,
            ]);
        } catch (\Exception $e) {
            $existFileId = $this->isFileExist($serviceEntity->id, $toEntityId, $fileEntity->hash);
            \App::$domain->storage->person->deleteById($existFileId);
            \App::$domain->storage->file->updateById($id, [
                'service_id' => $serviceEntity->id,
                'entity_id' => $toEntityId,
            ]);
        }

        $targetFileEntity = \App::$domain->storage->file->oneById($id, $query);
        $targetFileEntity->service_id = $serviceEntity->id;
        $targetFileEntity->entity_id = $toEntityId;
        \App::$domain->storage->server->move($fileEntity, $targetFileEntity);
    }

    public function filesSize(Query $query) {
        $query->select(['SUM(size) AS size']);
        return $this->one($query);
    }

    private function isFileExist(int $serviceId, int $entityId, string $hash) {
        try {
            $fileEntity = \App::$domain->storage->file->oneByServiceIdAndEntityIdAndFileHash($serviceId, $entityId, $hash);
        } catch (NotFoundHttpException $e) {
            return false;
        }
        return $fileEntity->id;
    }

}
