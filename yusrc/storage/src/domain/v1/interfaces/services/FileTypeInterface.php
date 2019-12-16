<?php

namespace yubundle\storage\domain\v1\interfaces\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\storage\domain\v1\entities\FileExtensionEntity;
use yubundle\storage\domain\v1\entities\FileTypeEntity;

/**
 * Interface FileTypeInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileTypeInterface $repository
 */
interface FileTypeInterface extends CrudInterface {

    public function oneByCode($name, Query $query = null) : FileTypeEntity;

}
