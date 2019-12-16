<?php

namespace yubundle\user\domain\v1\services;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\services\base\BaseService;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\user\domain\v1\entities\PersonEntity;
use yubundle\user\domain\v1\forms\UploadForm;

class AvatarService extends BaseService {

    public $serviceId = 3;

    public function create(UploadForm $model) {
        $personEntity = \App::$domain->user->person->oneSelf();
        $model->validate();
        if ($model->hasErrors()) {
            throw new UnprocessableEntityHttpException($model->errors);
        }
        $uploadedFile = StorageHelper::forgeUploaded($_FILES, 'file');
        /** @var FileEntity[] $collection */
        $collection = \App::$domain->storage->person->saveUploadedCollection(['file' => $uploadedFile], $this->serviceId, $personEntity->id, null);
        $personEntity->avatar = $collection[0]->file_path;
        \App::$domain->user->person->updateSelf($personEntity);
        return $collection[0];
    }

    public function delete() {
        $personEntity = \App::$domain->user->person->oneSelf();
        $fileHash = FileHelper::fileNameOnly($personEntity->avatar);
        $fileEntity = \App::$domain->storage->file->repository->oneByServiceIdAndEntityIdAndFileHash($this->serviceId, $personEntity->id, $fileHash);
        \App::$domain->storage->person->deleteById($fileEntity->id);
        $personEntity->avatar = null;
        \App::$domain->user->person->updateSelf($personEntity);
    }

}