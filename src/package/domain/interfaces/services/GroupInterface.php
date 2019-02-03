<?php

namespace yii2rails\extension\package\domain\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;
use yii2rails\domain\data\Query;
use yii2rails\extension\package\domain\entities\GroupEntity;

/**
 * Interface GroupInterface
 * 
 * @package yii2rails\extension\package\domain\interfaces\services
 * 
 * @property-read \yii2rails\extension\package\domain\Domain $domain
 * @property-read \yii2rails\extension\package\domain\interfaces\repositories\GroupInterface $repository
 */
interface GroupInterface extends CrudInterface {

    /**
     * @param $name
     * @param Query|null $query
     * @return GroupEntity
     */
    public function oneByName($name, Query $query = null);
    public function allNames(Query $query = null);

}
