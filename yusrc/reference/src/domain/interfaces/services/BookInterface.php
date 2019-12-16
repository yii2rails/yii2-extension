<?php

namespace yubundle\reference\domain\interfaces\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\reference\domain\entities\BookEntity;

/**
 * Interface ItemInterface
 * 
 * @package yubundle\reference\domain\interfaces\services
 * 
 * @property-read \yubundle\reference\domain\Domain $domain
 * @property-read \yubundle\reference\domain\interfaces\repositories\BookInterface $repository
 */
interface BookInterface extends CrudInterface {

    public function oneByEntity(string $entity, Query $query = null) : BookEntity;

}
