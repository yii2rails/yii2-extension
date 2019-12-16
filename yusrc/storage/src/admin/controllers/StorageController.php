<?php

namespace yubundle\storage\admin\controllers;

use App;
use yii2rails\domain\data\Query;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yubundle\storage\admin\forms\UploadForm;
use kartik\alert\Alert;
use Yii;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\web\ActiveController as Controller;

class StorageController extends Controller {

    public $service = 'storage.person';
    public $formClass = UploadForm::class;
    public $titleName = 'id';

    public function actions() {
        $actions = parent::actions();
        $actions['index']['render'] = 'index';
        $actions['view']['render'] = 'view';
        $actions['view']['query'] = Query::forge()->with(['service.thumbs', 'ext.type']);
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate() {
        $model = new UploadForm();
        if(Yii::$app->request->isPost) {
            if($model->validate()) {
                try{
                    App::$domain->storage->person->saveUploaded($model);
                    App::$domain->navigation->alert->create(['storage/storage', 'uploaded_success'], Alert::TYPE_SUCCESS);

                    $this->redirect('/storage/storage');
                } catch (UnprocessableEntityHttpException $e){
                    $model->addErrorsFromException($e);
                } catch (AlreadyExistsException $e) {
                    $model->addError('file', $e->getMessage());
                }
            }
        }
        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionUpload()
    {
        $model = new UploadForm();
        if(Yii::$app->request->isPost) {
            if($model->validate()) {
                try{
                    $file = App::$domain->storage->person->saveUploaded($model);
                    $fileArray = $file->toArray();
                    $url = $fileArray['url'];
                    return $url;
                } catch (UnprocessableEntityHttpException $e){
                    $model->addErrorsFromException($e);
                }
            }
        }
    }

}