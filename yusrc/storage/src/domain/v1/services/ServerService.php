<?php

namespace yubundle\storage\domain\v1\services;

use League\Flysystem\File;
use yii2rails\domain\BaseEntity;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\interfaces\services\ServerInterface;
use yii2rails\domain\services\base\BaseActiveService;
use yii2rails\domain\services\base\BaseService;

/**
 * Class ServerService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\ServerInterface $repository
 */
class ServerService extends BaseService implements ServerInterface {

    public function move(FileEntity $entity, FileEntity $toEntity) {
        return $this->repository->move($entity, $toEntity);
    }


}
