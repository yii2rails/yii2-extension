<?php

namespace yubundle\staff\admin\controllers;

use yii\helpers\ArrayHelper;
use yii2rails\domain\data\Query;
use yii2rails\extension\web\helpers\ControllerHelper;
use yubundle\staff\admin\forms\DivisionForm;
use yii2rails\domain\web\ActiveController as Controller;

class DivisionController extends Controller
{

    const RENDER_INDEX = '@yubundle/staff/admin/views/division/index';
    const RENDER_VIEW = '@yubundle/staff/admin/views/division/view';

    public $titleName = 'name';

    public $formClass = DivisionForm::class;

    public $service = 'staff.division';

    public function actions() {
        $actions = parent::actions();
        $actions['view']['render'] = self::RENDER_VIEW;
        unset($actions['index']);
        //$actions['index']['render'] = self::RENDER_INDEX;
        return $actions;
    }

    public function actionIndex() {
        $data = \Yii::$app->request->get();
        $parentId = ArrayHelper::getValue($data, 'parent_id', null);
        if ($parentId == '{null}') {
            $parentId = null;
        }
        $query = new Query();
        $query->andWhere(['parent_id' => $parentId]);
        $dataProvider = \App::$domain->staff->division->getDataProvider($query);
        return $this->render('index', ['dataProvider' => $dataProvider ]);
    }

}
