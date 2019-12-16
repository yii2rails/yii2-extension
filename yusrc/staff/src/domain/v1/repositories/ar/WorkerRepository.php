<?php

namespace yubundle\staff\domain\v1\repositories\ar;

use yii2rails\domain\data\Query;
use yubundle\staff\domain\v1\interfaces\repositories\WorkerInterface;
use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\domain\enums\RelationEnum;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class WorkerRepository
 * 
 * @package yubundle\staff\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 */
class WorkerRepository extends BaseActiveArRepository implements WorkerInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'staff_worker';
    }

    public function allByPersonIds(array $personIds) {
        if(empty($personIds)) {
            return [];
        }

        $query = new Query;
        $query->andWhere(['person_id' => $personIds]);
        $query->with('person.user');
        $collection = $this->all($query);
        return $collection;
    }

    public function allByPostIds(array $postIds) {
        if(empty($postIds)) {
            return [];
        }
        $query = new Query;
        $query->andWhere(['post_id' => $postIds]);
        $query->with('person.user');
        $collection = $this->all($query);
        return $collection;
    }

    public function checkExistWorkerByPersonId($personId) {
        $query = new Query();
        $query->andWhere(['person_id' => $personId]);
        $workerEntity = \App::$domain->staff->worker->repository->one($query);
        return $workerEntity;
    }

}
