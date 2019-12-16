<?php

namespace yubundle\reference\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2rails\domain\data\Query;
use yii2rails\domain\web\ActiveController as Controller;
use yii\filters\VerbFilter;
use yii2rails\extension\web\helpers\Behavior;

class ItemController extends Controller
{

    const RENDER_INDEX = '@yubundle/reference/admin/views/item/index';

    public $formClass = 'yubundle\reference\admin\forms\ItemForm';

    public $titleName = 'value';

    public $service = 'reference.item';

    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        //$actions['index']['render'] = self::RENDER_INDEX;
        $actions['view']['render'] = 'view';
        return $actions;
    }

    public function actionIndex() {
        $data = \Yii::$app->request->get();
        $referenceBookId = ArrayHelper::getValue($data, 'reference_book_id', null);
        if ($referenceBookId == null | $referenceBookId == '{null}') {
            $companyId = \App::$domain->account->auth->identity->company_id;
            $bookEntity = \App::$domain->reference->book->repository->checkExistsBookByCompanyId($companyId);
            $referenceBookId = $bookEntity->id;
        }
        $query = new Query();
        $query->andWhere(['reference_book_id' => $referenceBookId]);
        $dataProvider = \App::$domain->reference->item->getDataProvider($query);
        return $this->render('index', ['dataProvider' => $dataProvider ]);
    }

}
