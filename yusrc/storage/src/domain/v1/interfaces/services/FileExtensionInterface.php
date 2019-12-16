<?php

namespace yubundle\storage\domain\v1\interfaces\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface FileExtensionInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileExtensionInterface $repository
 */
interface FileExtensionInterface extends CrudInterface {

    public function allByTypeId($typeId, Query $query = null);

}
