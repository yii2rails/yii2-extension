<?php

namespace yubundle\staff\admin\controllers;

use yubundle\staff\admin\forms\CompanyForm;
use yii2rails\domain\web\ActiveController as Controller;

class CompanyController extends Controller
{

    const RENDER_INDEX = '@yubundle/staff/admin/views/company/index';
    const RENDER_VIEW = '@yubundle/staff/admin/views/company/view';

    public $titleName = 'name';

    public $formClass = CompanyForm::class;

    public $service = 'staff.company';

    public function actions() {
        $actions = parent::actions();
        $actions['view']['render'] = self::RENDER_VIEW;
        $actions['index']['render'] = self::RENDER_INDEX;
        return $actions;
    }

}
