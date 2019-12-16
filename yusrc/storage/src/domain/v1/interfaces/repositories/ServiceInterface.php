<?php

namespace yubundle\storage\domain\v1\interfaces\repositories;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\repositories\CrudInterface;
use yubundle\storage\domain\v1\entities\ServiceEntity;

/**
 * Interface ServiceInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\repositories
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
interface ServiceInterface extends CrudInterface {

    public function oneByDir(string $dir, Query $query = null) : ServiceEntity;

}
