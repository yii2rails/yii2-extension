<?php

namespace yubundle\storage\api\controllers;

use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use yii2lab\rest\domain\rest\ActiveControllerWithQuery as Controller;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2rails\extension\web\helpers\Behavior;
use yubundle\storage\admin\forms\UploadForm;
use yubundle\storage\domain\v1\helpers\StorageHelper;

class PersonController extends Controller
{

	public $service = 'storage.person';

	public function behaviors()
    {
        return [
            Behavior::cors(),
            Behavior::auth(),
            Behavior::verb([
                'upload' => ['POST'],
            ]),
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    // todo: реализовать загрузку файлов себе в альбом, если не указан service_id или entity_id

    public function actionCreate() {
	    if(empty($_FILES)) {
	        throw new BadRequestHttpException(\Yii::t('storage/storage', 'select_files_for_upload_message'));
        }
        $uploadedCollection = StorageHelper::forgeUploadedCollection($_FILES);
	    $serviceId = \Yii::$app->request->post('service_id');
        //$entityId = \Yii::$app->request->post('entity_id');
        $entityId = 0;
        $serviceId = intval($serviceId);
        $collection = \App::$domain->storage->person->saveUploadedCollection($uploadedCollection, $serviceId, $entityId);
        return $collection;
    }

}

/*
$loginForm = new LoginForm;
$loginForm->login = '77771111111';
$loginForm->password = 'Wwwqqq111';
$loginEntity = \App::$domain->account->auth->authenticationFromApi($loginForm);

$requestEntity = new RequestEntity;
$requestEntity->uri = 'http://api.yumail.project/v1/file-storage';
$requestEntity->method = HttpMethodEnum::POST;
$requestEntity->headers = [
    'Authorization' => $loginEntity->token,
];
$requestEntity->data = [
    'service_id' => 1,
];
$requestEntity->files = [
    'file1' => ROOT_DIR . "/composer.json",
    'file2' => ROOT_DIR . "/composer.lock",
];
$responseEntity = RestHelper::sendRequest($requestEntity);
d($responseEntity->data);
 */
