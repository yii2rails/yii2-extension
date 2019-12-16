<?php

namespace yubundle\common\dev\api\controllers;

use Yii;
use App;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\web\enums\HttpStatusCodeEnum;
use yii2rails\extension\web\helpers\Behavior;
use yii2lab\rest\domain\rest\Controller;
use yubundle\common\dev\domain\helpers\DeleteUserHelper;

class UserController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'cors' => Behavior::cors(),
			'authenticator' => Behavior::auth(),
            'access' => Behavior::access(['oAccountManage']),

		];
	}

	public function actionDelete($phone)
	{
		DeleteUserHelper::delete($phone);
        Yii::$app->response->setStatusCode(HttpStatusCodeEnum::NO_CONTENT);
	}

}
