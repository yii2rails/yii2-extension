<?php

namespace yubundle\account\domain\v2\chain;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\common\enums\StatusEnum;
use yubundle\account\domain\v2\chain\BaseHandler;
use yubundle\account\domain\v2\forms\registration\PersonInfoForm;
use yubundle\staff\domain\v1\entities\CompanyEntity;

class LoginHandler extends BaseHandler {

    public function get($request) {
        $login = ArrayHelper::getValue($request, 'login');
        try {
            \App::$domain->account->login->isExistsByLogin($login);
            return;
        } catch (NotFoundHttpException $e) {
            $companyEntity = $request['entities']['company'];
            $personInfoForm = new PersonInfoForm();
            $personInfoForm->login = $login;
            $personInfoForm->last_name = ArrayHelper::getValue($request, 'last_name');
            $personInfoForm->first_name =  ArrayHelper::getValue($request, 'name');
            $personInfoForm->middle_name = ArrayHelper::getValue($request, 'middle_name');
            $personInfoForm->phone = ArrayHelper::getValue($request, 'mobile'); //'telephone_number'
            $personInfoForm->birthday_day = ArrayHelper::getValue($request, 'birthday_day');
            $personInfoForm->birthday_month = ArrayHelper::getValue($request, 'birthday_month');
            $personInfoForm->birthday_year = ArrayHelper::getValue($request, 'birthday_year');
            $personInfoForm->password = ArrayHelper::getValue($request, 'password');
            $personInfoForm->password_confirm = ArrayHelper::getValue($request, 'password');
            $personInfoForm->birth_date = ArrayHelper::getValue($request, 'birth_date', date('Y-m-d'));
            $personInfoForm->company_id = $companyEntity->id;

            $loginEntity = \App::$domain->account->login->createWeb($personInfoForm);
            $request['entities']['login'] = $loginEntity;
            $bookHandler = new BookHandler();
            $this->setNextHandler($bookHandler);
            $this->nextHandle($request);
        }
    }
}
