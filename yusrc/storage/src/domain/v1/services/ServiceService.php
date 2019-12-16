<?php

namespace yubundle\storage\domain\v1\services;

use yii2rails\domain\data\Query;
use yubundle\storage\domain\v1\entities\ServiceEntity;
use yubundle\storage\domain\v1\interfaces\services\ServiceInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ServiceService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\ServiceInterface $repository
 */
class ServiceService extends BaseActiveService implements ServiceInterface {

    public function oneByCode($name, Query $query = null) : ServiceEntity
    {
        $query = Query::forge($query);
        $query->andWhere(['code' => $name]);
        return $this->one($query);
    }

}
