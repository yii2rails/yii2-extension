<?php

namespace yubundle\storage\domain\v1\interfaces\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\storage\domain\v1\entities\ServiceEntity;

/**
 * Interface ServiceInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\ServiceInterface $repository
 */
interface ServiceInterface extends CrudInterface {

    public function oneByCode($name, Query $query = null) : ServiceEntity;

}
