<?php

namespace yubundle\account\api\v2\controllers;

use Yii;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\store\StoreFile;
use yii2rails\extension\web\helpers\Behavior;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2lab\rest\domain\rest\Controller;
use yubundle\account\console\forms\PseudoLoginForm;
use yubundle\account\domain\v2\interfaces\services\AuthInterface;

/**
 * Class AuthController
 *
 * @package yubundle\account\api\v2\controllers
 * @property AuthInterface $service
 */
class OauthController extends Controller
{
	
	public $service = 'account.auth';
	
	/**
	 * @inheritdoc
	 */
	/*public function behaviors()
	{
		return [
			'cors' => Behavior::cors(),
			'authenticator' => Behavior::auth(['info']),
		];
	}*/
	
	/**
	 * @inheritdoc
	 */
	/*protected function verbs()
	{
		return [
			'login' => ['POST'],
			'info' => ['GET'],
		];
	}*/
	
	/**
	 * @inheritdoc
	 */
	/*public function actions()
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
	}*/
	
	public function actionRegister()
	{
		
		$store = new StoreFile(ROOT_DIR . '/rr.json');
		$all = $store->load();
		$all[] = \Yii::$app->request->queryParams;
		$store->save($all);
		return \Yii::$app->request->queryParams;
	}
	
}