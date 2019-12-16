<?php

namespace yubundle\account\domain\v2\chain;

use App;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\common\enums\StatusEnum;
use yubundle\account\domain\v2\chain\BaseHandler;
use yubundle\account\domain\v2\chain\LoginHandler;
use yubundle\staff\domain\v1\entities\CompanyEntity;

class CompanyHandler extends BaseHandler {

    public function get($request) {
        $companyName = ArrayHelper::getValue($request, 'company');
        try {
            $companyEntity = \App::$domain->staff->company->repository->checkExistsCompanyByName($companyName);
        } catch (NotFoundHttpException $e) {
            $companyEntity = new CompanyEntity();
            $companyEntity->name = $companyName;
            $companyEntity->code = ArrayHelper::getValue($request, 'company_code', rand(10, 20));
            $companyEntity->status = StatusEnum::ENABLE;
            $companyEntity = App::$domain->staff->company->repository->insert($companyEntity);
        }
        $request['entities']['company'] = $companyEntity;
        $loginHandler = new LoginHandler();
        $this->setNextHandler($loginHandler);
        $this->nextHandle($request);
    }

}
