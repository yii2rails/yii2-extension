<?php

namespace yubundle\account\domain\v2\chain;


use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yubundle\account\domain\v2\chain\BaseHandler;
use yubundle\staff\domain\v1\entities\WorkerEntity;
use yii2rails\domain\data\Query;

class WorkerHandler extends BaseHandler {

    public function get($request) {
        $loginEntity = $request['entities']['login'];
        try {
            \App::$domain->staff->worker->repository->checkExistWorkerByPersonId($loginEntity->person_id);
        } catch (NotFoundHttpException $e) {
            $divisionEntity = $request['entities']['division'];
            $loginEntity = $request['entities']['login'];
            $companyEntity = $request['entities']['company'];
            $bookEntity = $request['entities']['book'];

            $workerEntity = new WorkerEntity();
            $workerEntity->division_id = $divisionEntity->id;
            $workerEntity->phone = ArrayHelper::getValue($request, 'telephone_number');
            $workerEntity->email = ArrayHelper::getValue($request, 'mail');
            $workerEntity->post_id = $bookEntity->id;
            $workerEntity->person_id = $loginEntity->person_id;
            $workerEntity->company_id = $companyEntity->id;
            $workerEntity = \App::$domain->staff->worker->repository->insert($workerEntity);
        }
        $accountHandler = new AccountHandler();
        $this->setNextHandler($accountHandler);
        $this->nextHandle($request);
    }

}
