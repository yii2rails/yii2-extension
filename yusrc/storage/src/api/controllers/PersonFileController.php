<?php

namespace yubundle\storage\api\controllers;

use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use yii2lab\rest\domain\rest\ActiveControllerWithQuery as Controller;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\web\helpers\Behavior;
use yubundle\storage\admin\forms\UploadForm;
use yubundle\storage\domain\v1\helpers\StorageHelper;

class PersonFileController extends Controller
{

	public $service = 'storage.person';

	public function behaviors()
    {
        return [
            Behavior::cors(),
            Behavior::auth(),
            Behavior::verb([
                'create' => ['POST'],
            ]),
        ];
    }

    public function actions()
    {
        $actons = parent::actions();
        unset($actons['create']);
        return $actons;
    }

    public function actionCreate()
    {
        $model = new UploadForm;
        Helper::forgeForm($model);
        $fileEntity = \App::$domain->storage->person->uploadPersonal($model);
        \Yii::$app->response->setStatusCode(201);
        \Yii::$app->response->headers->add(HttpHeaderEnum::X_ENTITY_ID, $fileEntity->id);
    }

}
