<?php

namespace yii2lab\extension\package\domain\services;

use yii2lab\domain\behaviors\query\QueryFilter;
use yii2lab\extension\package\domain\interfaces\services\GroupInterface;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\domain\data\Query;
use yii2lab\extension\yii\helpers\ArrayHelper;

/**
 * Class GroupService
 * 
 * @package yii2lab\extension\package\domain\services
 * 
 * @property-read \yii2lab\extension\package\domain\Domain $domain
 * @property-read \yii2lab\extension\package\domain\interfaces\repositories\GroupInterface $repository
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
