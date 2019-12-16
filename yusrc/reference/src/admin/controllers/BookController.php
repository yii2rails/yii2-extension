<?php

namespace yubundle\reference\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2rails\domain\data\Query;
use yii2rails\domain\web\ActiveController as Controller;
use yii\filters\VerbFilter;
use yii2rails\extension\web\helpers\Behavior;

class BookController extends Controller
{

    const RENDER_INDEX = '@yubundle/reference/admin/views/book/index';

    public $formClass = 'yubundle\reference\admin\forms\BookForm';

    public $service = 'reference.book';

    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        $actions['view']['render'] = 'view';
        return $actions;
    }

    public function actionIndex() {
        $companyId = \App::$domain->account->auth->identity->company_id;
        $query = new Query();
        $query->andWhere(['owner_id' => $companyId]);
        $dataProvider = \App::$domain->reference->book->getDataProvider($query);
        return $this->render('index', ['dataProvider' => $dataProvider ]);
    }

}
