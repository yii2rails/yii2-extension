<?php

namespace yubundle\staff\domain\v1\behaviors;

use yii2rails\domain\behaviors\query\BaseQueryFilter;
use yii2rails\domain\data\Query;

class WorkerQueryBehavior extends BaseQueryFilter
{
    public function prepareQuery(Query $query)
    {
        $workerEntity = \App::$domain->staff->worker->oneSelf();
        $query = Query::forge($query);
        $query->andWhere([
            'company_id' => $workerEntity->company_id
        ])
            ->andWhere(['!=', 'person_id', $workerEntity->person_id])
            ->with('person.user')
            ->with('division')
            ->with('post');
    }
}