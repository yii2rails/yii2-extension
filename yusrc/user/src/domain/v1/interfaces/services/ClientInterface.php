<?php

namespace yubundle\user\domain\v1\interfaces\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\user\domain\v1\entities\ClientEntity;

/**
 * Interface ClientInterface
 * 
 * @package yubundle\user\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\user\domain\v1\Domain $domain
 * @property-read \yubundle\user\domain\v1\interfaces\repositories\ClientInterface $repository
 */
interface ClientInterface extends CrudInterface {

    public function oneByPersonId($personId, Query $query = null) : ClientEntity;
	
}
