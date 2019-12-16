<?php

namespace yubundle\user\domain\v1\interfaces\services;

use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\user\domain\v1\entities\PersonEntity;

/**
 * Interface PersonInterface
 * 
 * @package yubundle\user\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\user\domain\v1\Domain $domain
 * @property-read \yubundle\user\domain\v1\interfaces\repositories\PersonInterface $repository
 */
interface PersonInterface extends CrudInterface {
	
	public function isExistsByPhone($phone);

    /**
     * @param $phone
     * @param Query|null $query
     * @return PersonEntity
     */
	public function oneByPhone($phone, Query $query = null);
    public function oneSelf(Query $query = null);
    public function updateSelf(PersonEntity $personEntity);

}
