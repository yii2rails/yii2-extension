<?php

namespace yubundle\account\domain\v2\chain;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\chain\BaseHandler;
use yubundle\staff\domain\v1\entities\DivisionEntity;

class DivisionHandler extends BaseHandler {

    public function get($request) {
        $companyEntity = $request['entities']['company'];
        $divisionName = ArrayHelper::getValue($request, 'department');
        try {
            $divisionEntity = \App::$domain->staff->division->repository->checkExistDivisionByNameAncCompanyId($divisionName, $companyEntity->id);
        } catch (NotFoundHttpException $e) {
            $divisionEntity = new DivisionEntity();
            $divisionEntity->name = $divisionName;
            $divisionEntity->company_id = $companyEntity->id;
            $divisionEntity = \App::$domain->staff->division->repository->insert($divisionEntity);
        }
        $request['entities']['division'] = $divisionEntity;
        $postHandler = new PostHandler();
        $this->setNextHandler($postHandler);
        $this->nextHandle($request);
    }

}
