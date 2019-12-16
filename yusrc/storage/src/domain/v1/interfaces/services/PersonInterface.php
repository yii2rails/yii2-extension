<?php

namespace yubundle\storage\domain\v1\interfaces\services;

use yii\web\UploadedFile;
use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\storage\admin\forms\UploadForm;
use yubundle\storage\domain\v1\entities\FileEntity;

/**
 * Interface PersonInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\PersonInterface $repository
 */
interface PersonInterface extends CrudInterface {

    public function saveUploadedNew(UploadForm $model) : FileEntity;

    /**
     * Загрузка файла из формы в хранилище
     *
     * @param UploadForm $model
     * @return mixed
     */
    public function saveUploaded(UploadForm $model, $encoding);

    public function uploadPersonal(UploadForm $model): FileEntity;

    public function saveUploadedCollection(array $uploadedCollection, int $serviceId, int $entityId, $encoding = null) : array;

    public function oneByPath(string $path) : FileEntity;
}
