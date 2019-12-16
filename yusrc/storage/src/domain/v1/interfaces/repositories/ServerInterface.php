<?php

namespace yubundle\storage\domain\v1\interfaces\repositories;

use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\entities\StorageEntity;

/**
 * Interface ServerInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\repositories
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
interface ServerInterface {

    /**
     * Получение всех файлов пользователя
     * @param $ownerId
     * @return array
     */
    //public function allByOwner($ownerId): array;

    /**
     * Загрузка файла в хранилище
     *
     * @param $fileName
     * @return StorageEntity
     */
    //public function saveFile($fileName);

    /**
     * Выборка одного файла из хранилища
     *
     * @param $id
     * @return StorageEntity
     */
    //public function oneById($id): StorageEntity;

}
