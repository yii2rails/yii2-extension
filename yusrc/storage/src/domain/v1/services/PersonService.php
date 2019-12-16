<?php

namespace yubundle\storage\domain\v1\services;

use yii2rails\domain\behaviors\query\QueryFilter;
use yii2rails\domain\data\Query;
use yii2rails\domain\enums\ActiveMethodEnum;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\storage\admin\forms\UploadForm;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\storage\domain\v1\interfaces\services\PersonInterface;
use yii2rails\domain\services\base\BaseActiveService;
use yii\web\NotFoundHttpException;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yubundle\storage\domain\v1\exceptions\FreeSpaceOverException;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\entities\StorageEntity;
use yubundle\storage\domain\v1\interfaces\services\StorageInterface;
use yii\web\UploadedFile;
use yii2rails\domain\services\base\BaseService;
use yii2rails\extension\common\helpers\TempHelper;

use yii\base\Exception;

/**
 * Class PersonService
 *
 * @package yubundle\storage\domain\v1\services
 *
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\PersonInterface $repository
 */
class PersonService extends BaseActiveService implements PersonInterface {

    public function getRepository($name = null)
    {
        return $this->domain->repositories->file;
    }

    public function sort() {
		return [
			'attributes'=>[
				'id',
				'size',
				'url',
			],
		];
	}

	public function behaviors()
    {
        return [
            [
                'class' => QueryFilter::class,
                'method' => 'with',
                'params' => 'service',
            ],
        ];
    }

    public function oneByPath(string $path) : FileEntity
    {
        $filePathInfo = StorageHelper::parseFilePath($path);
        $filePathInfo['profileDir'] = str_replace('storage/', '', $filePathInfo['profileDir']);
        $serviceEntity = \App::$domain->storage->repositories->service->oneByDir($filePathInfo['profileDir']);
        $fileEntity = \App::$domain->storage->repositories->file->oneByServiceIdAndEntityIdAndFileHash($serviceEntity->id, $filePathInfo['entityId'], $filePathInfo['fileName']);
        return $fileEntity;
    }

    public function deleteById($id)
    {
        $this->beforeAction(self::EVENT_DELETE);
        $entity = $this->oneById($id);
        $this->domain->repositories->server->delete($entity);
        $this->domain->repositories->file->delete($entity);
        return $this->afterAction(self::EVENT_DELETE);
    }

    public function uploadPersonal(UploadForm $model): FileEntity
    {
        $serviceEntity = \App::$domain->storage->service->oneByCode('person_temp');
        $model->service_id = $serviceEntity->id;
        $model->entity_id = \App::$domain->account->auth->identity->person_id;
        $model->validate();
        if ($model->hasErrors()) {
            throw new UnprocessableEntityHttpException($model->errors);
        }
        //$uploadedFile = StorageHelper::forge('file');
        /** @var FileEntity[] $collection */
        $collection = \App::$domain->storage->person->saveUploadedCollection(['file' => $model->file], $model->service_id, $model->entity_id);
        $fileEntity = $collection[0];
        return $fileEntity;
    }

    public function saveUploadedNew(UploadForm $model) : FileEntity {
        $model->file = StorageHelper::forge('file');
        $model->validate();
        if ($model->hasErrors()) {
            throw new UnprocessableEntityHttpException($model->errors);
        }
        /** @var FileEntity $fileEntity */
        $fileEntity = \App::$domain->storage->person->saveUploaded($model, null);
        return $fileEntity;
    }

    public function saveUploaded(UploadForm $model, $fileEncoding) {
        $tempFile = TempHelper::copyUploadedToTemp($model->file);
        $hash = hash_file('crc32b', $tempFile);
        $fileEntity = null;
        try {
            /** @var FileEntity $fileEntity */
            $fileEntity = $this->domain->file->repository->oneByServiceIdAndEntityIdAndFileHash($model->service_id, $model->entity_id, $hash);
            //throw new AlreadyExistsException(\Yii::t('storage/storage' ,'already_exists'));
        } catch (NotFoundHttpException $e) {
            // todo: сделать системной настройкой для storage
            if(\App::$domain->has('mail')) {
                $currentFreeSpaceBoxBytes = (\App::$domain->mail->mail->checkFreeSpaceInBox() * 1024);
                $residualFreeSpaceBoxBytes = $currentFreeSpaceBoxBytes - $model->file->size;
                $residualFreeSpaceBoxKB = round(floatval(($residualFreeSpaceBoxBytes / 1024)), 2);
                if ($residualFreeSpaceBoxKB <= 0) {
                    throw new FreeSpaceOverException(\Yii::t('storage/storage' ,'not_enough_free_space'), 201);
                }
            }
            $fileEntity = $this->forgeFileEntity($tempFile, $model->service_id);
            $fileEntity->entity_id = $model->entity_id;
            $fileEntity->description = $model->description;
            $this->domain->repositories->server->insert($fileEntity, $tempFile, $fileEncoding);
            $this->domain->file->repository->insert($fileEntity);
        }
        return $fileEntity;
    }

    public function saveUploadedCollection(array $uploadedCollection, int $serviceId, int $entityId, $fileEncoding = null) : array {
        /** @var FileEntity[] $collection */
        $collection = [];
        /** @var UploadedFile $uploadedFile */
        foreach ($uploadedCollection as $name => $uploadedFile) {
            $model = new UploadForm;
            $model->file = $uploadedFile;
            $model->service_id = $serviceId;
            $model->entity_id = $entityId;
            $error = new ErrorCollection;
            try {
                $uploadEntity = $this->saveUploaded($model, $fileEncoding);
                $collection[] = $uploadEntity;
            } catch (AlreadyExistsException | FreeSpaceOverException $e) {
                $error->add($name, $e->getMessage());
            }
        }
        /*
        if($error->has()) {
            throw new UnprocessableEntityHttpException($error);
        }
        */
        return $collection;
    }

    private function forgeFileEntity(string $fileName, int $serviceId) : FileEntity {
        $fileEntity = new FileEntity;
        $fileEntity->extension = FileHelper::fileExt($fileName);
        $fileEntity->name = FileHelper::mb_basename($fileName);
        $fileEntity->hash = hash_file('crc32b', $fileName);
        $fileEntity->size = filesize($fileName);
        $fileEntity->service_id = $serviceId;
        try {
            $fileEntity->service = \App::$domain->storage->service->oneById($serviceId);
        } catch (NotFoundHttpException $e) {
            $error = new ErrorCollection;
            $error->add('service_id', 'main', 'not_found');
        }
        return $fileEntity;
    }

}
