<?php

namespace yubundle\storage\domain\v1\services;

use yii2rails\domain\data\Query;
use yubundle\storage\domain\v1\entities\FileExtensionEntity;
use yubundle\storage\domain\v1\entities\FileTypeEntity;
use yubundle\storage\domain\v1\interfaces\services\FileTypeInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class FileTypeService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileTypeInterface $repository
 */
class FileTypeService extends BaseActiveService implements FileTypeInterface {

    public function oneByCode($name, Query $query = null) : FileTypeEntity
    {
        $query = Query::forge($query);
        $query->andWhere(['code' => $name]);
        return $this->one($query);
    }

}
