<?php

namespace yubundle\storage\domain\v1\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\storage\domain\v1\entities\FileEntity;

/**
 * Interface FileInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileInterface $repository
 */
interface FileInterface extends CrudInterface {

    public function oneByServiceIdAndEntityId(int $serviceId, int $entityId) : FileEntity;
    public function moveAll(array $fileIds, string $toServiceCode, int $toEntityId);
    public function move(int $id, string $toServiceCode, int $toEntityId);

}
