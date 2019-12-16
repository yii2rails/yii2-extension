<?php

namespace yubundle\storage\domain\v1\services;

use yii2rails\domain\data\Query;
use yubundle\storage\domain\v1\interfaces\services\FileExtensionInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class FileExtensionService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileExtensionInterface $repository
 */
class FileExtensionService extends BaseActiveService implements FileExtensionInterface {

    public function allByTypeId($typeId, Query $query = null) {
        $query = new Query;
        $query->andWhere([
            'type_id' => $typeId,
        ]);
        return $this->all($query);
    }

}
