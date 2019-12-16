<?php

namespace yubundle\storage\domain\v1\interfaces\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface PolicyInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\PolicyInterface $repository
 */
interface PolicyInterface extends CrudInterface {

    public function oneByRole($role, Query $query = null);
    
}
