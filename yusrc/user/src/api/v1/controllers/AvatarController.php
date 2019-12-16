<?php

namespace yubundle\user\api\v1\controllers;

use yubundle\user\domain\v1\forms\UploadForm;
use yii2lab\rest\domain\rest\ActiveControllerWithQuery as Controller;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\web\helpers\Behavior;

class AvatarController extends Controller{

    public $service = 'user.avatar';

    public function behaviors()
    {
        return [
            Behavior::cors(),
            Behavior::auth(),
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['create']);
        //$actions['delete']['serviceMethod'] = 'delete';
        return $actions;
    }

    public function actionCreate()
    {
        $model = new UploadForm;
        Helper::forgeForm($model);
        $fileEntity = \App::$domain->user->avatar->create($model);
        \Yii::$app->response->setStatusCode(201);
        \Yii::$app->response->headers->add(HttpHeaderEnum::X_ENTITY_ID, $fileEntity->id);
    }

    public function actionDelete()
    {
        \App::$domain->user->avatar->delete();
    }

}