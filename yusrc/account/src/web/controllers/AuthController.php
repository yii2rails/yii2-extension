<?php
namespace yubundle\account\web\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii2lab\applicationTemplate\common\enums\ApplicationPermissionEnum;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\web\helpers\Behavior;
use yubundle\account\domain\v2\forms\LoginForm;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2bundle\navigation\domain\widgets\Alert;
use yubundle\account\domain\v2\helpers\AuthHelper;
use yii\web\Response;

/**
 * AuthController controller
 */
class AuthController extends Controller
{
	public $defaultAction = 'login';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['logout', 'get-token'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'actions' => ['login'],
						'allow' => true,
						'roles' => ['?'],
					],
				],
			],
			'verb' => Behavior::verb([
				'logout' => ['post'],
			]),
		];
	}

	/**
	 * Logs in a user.
	 */
	public function actionLogin()
	{
		$model = new LoginForm();
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            try {
                \App::$domain->account->auth->authenticationFromWeb($model);
                if(!$this->isBackendAccessAllowed()) {
                    \App::$domain->account->auth->logout();
                    \App::$domain->navigation->alert->create(['account/auth', 'login_access_error'], Alert::TYPE_DANGER);
                    return $this->goHome();
                }
                \App::$domain->navigation->alert->create(['account/auth', 'login_success'], Alert::TYPE_SUCCESS);
                return $this->goBack();
            } catch(UnprocessableEntityHttpException $e) {
                $model->addErrors($e->getErrorsForModel());
            }
        }

		return $this->render('login', [
			'model' => $model,
		]);
	}

	/**
	 * Logs out the current user.
	 */
	public function actionLogout($redirect = null)
	{
		\App::$domain->account->auth->logout();
		\App::$domain->navigation->alert->create(['account/auth', 'logout_success'], Alert::TYPE_SUCCESS);
		if($redirect) {
            return $this->redirect([SL . $redirect]);
        } else {
            return $this->goHome();
        }
	}

    public function actionGetToken()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return AuthHelper::getTokenString();
    }

	private function isBackendAccessAllowed()
	{
		if(APP != BACKEND) {
			return true;
		}
		if (Yii::$app->user->can(ApplicationPermissionEnum::BACKEND_ALL)) {
			return true;
		}
		return false;
	}
	
}
