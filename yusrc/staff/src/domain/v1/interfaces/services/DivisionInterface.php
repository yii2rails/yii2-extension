<?php

namespace yubundle\staff\domain\v1\interfaces\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface DivisionInterface
 * 
 * @package yubundle\staff\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 * @property-read \yubundle\staff\domain\v1\interfaces\repositories\DivisionInterface $repository
 */
interface DivisionInterface extends CrudInterface {

    public function tree(Query $query = null);

}
