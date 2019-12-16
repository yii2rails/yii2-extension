<?php

namespace yubundle\account\api\v2\controllers;

use Yii;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\web\helpers\Behavior;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2lab\rest\domain\rest\Controller;
use yii2woop\common\domain\account\v2\forms\AuthPseudoForm;
use yubundle\account\domain\v2\forms\LoginForm;
use yubundle\account\domain\v2\helpers\LDAPHelper;
use yubundle\account\domain\v2\interfaces\services\AuthInterface;

/**
 * Class AuthController
 *
 * @package yubundle\account\api\v2\controllers
 * @property AuthInterface $service
 */
class AuthController extends Controller
{
	
	public $service = 'account.auth';
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'cors' => Behavior::cors(),
			'authenticator' => Behavior::auth(['info']),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	protected function verbs()
	{
		return [
			'login' => ['POST'],
			'info' => ['GET'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'info' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'service' => Yii::$app->user,
				'successStatusCode' => 200,
				'serviceMethod' => 'getIdentity',
			],
			'options' => [
				'class' => 'yii\rest\OptionsAction',
			],
		];
	}
	
	public function actionLogin()
	{
		$body = Yii::$app->request->getBodyParams();
		try {
			$model = new LoginForm;
            Helper::forgeForm($model);
			$entity = $this->service->authenticationFromApi($model);
			Yii::$app->response->headers->set(HttpHeaderEnum::AUTHORIZATION, $entity->token);
			return $entity;
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
            return $e->getErrors();
		}
	}
	
	public function actionPseudo()
	{
		$body = Yii::$app->request->getBodyParams();
		try {
			
			$body = Helper::validateForm(AuthPseudoForm::class, $body);
			$address = ClientHelper::ip();
			$entity = \App::$domain->account->authPseudo->authentication($body['login'], $address, $body['email'], !empty($body['parentLogin']) ? $body['parentLogin'] : null);
			return $entity;
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
            return $e->getErrors();
		}
	}

    public function actionLoadLdap()
    {
        LDAPHelper::loadUserdata();
    }
}