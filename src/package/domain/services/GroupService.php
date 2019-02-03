<?php

namespace yii2rails\extension\package\domain\services;

use yii2rails\domain\behaviors\query\QueryFilter;
use yii2rails\extension\package\domain\interfaces\services\GroupInterface;
use yii2rails\domain\services\base\BaseActiveService;
use yii2rails\domain\data\Query;
use yii2rails\extension\yii\helpers\ArrayHelper;

/**
 * Class GroupService
 * 
 * @package yii2rails\extension\package\domain\services
 * 
 * @property-read \yii2rails\extension\package\domain\Domain $domain
 * @property-read \yii2rails\extension\package\domain\interfaces\repositories\GroupInterface $repository
 */
class GroupService extends BaseActiveService implements GroupInterface {

    public function behaviors()
    {
        return [
            [
                'class' => QueryFilter::class,
                'method' => 'with',
                'params' => 'provider',
            ],
        ];
    }

    public function oneByName($name, Query $query = null)
    {
        $query = Query::forge($query);
        $query->andWhere(['name' => $name]);
        return $this->one($query);
    }

    public function allNames(Query $query = null) {
        $collection = $this->all($query);
        return ArrayHelper::getColumn($collection, 'name');
    }

}
