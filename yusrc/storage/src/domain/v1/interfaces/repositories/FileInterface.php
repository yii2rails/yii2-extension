<?php

namespace yubundle\storage\domain\v1\interfaces\repositories;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\repositories\CrudInterface;
use yubundle\storage\domain\v1\entities\FileEntity;

/**
 * Interface FileInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\repositories
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
interface FileInterface extends CrudInterface {

    public function oneByHashAndEntityId($hash, int $entityId, Query $query = null) : FileEntity;

}
