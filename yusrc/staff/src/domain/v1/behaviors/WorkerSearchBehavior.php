<?php

namespace yubundle\staff\domain\v1\behaviors;

use App;
use yii\helpers\ArrayHelper;
use yii2rails\domain\behaviors\query\BaseQueryFilter;
use yii2rails\domain\data\Query;

class WorkerSearchBehavior extends BaseQueryFilter
{
    public function prepareQuery(Query $query)
    {
        $search = $query->getWhere('search');
        $query->removeWhere('search');

        if (isset($search['text'])) {

            $search = $search['text'];

            $personQuery = new Query;
            $personQuery->andWhere(['search' => ['name' => $search]]);
            $personCollection = App::$domain->user->person->all($personQuery);
            $personIds = ArrayHelper::getColumn($personCollection, 'id');

            $workerQuery = new Query;
            $workerQuery->andWhere([
               'or',
               ['like', 'email', strtolower($search)],
               ['like', 'phone', $search],
            ]);
            $workerCollection = App::$domain->staff->repositories->worker->all($workerQuery);
            $workerIds = ArrayHelper::getColumn($workerCollection, 'id');

            $query->andWhere([
                'or',
                ['person_id' => $personIds],
                ['id' => $workerIds]
            ]);
        }


    }
}