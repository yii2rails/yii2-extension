<?php

namespace yubundle\account\web\controllers;

use App;
use yii\web\NotFoundHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yubundle\account\domain\v2\exceptions\ConfirmAttemptException;
use yubundle\account\domain\v2\exceptions\ConfirmIncorrectCodeException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\web\helpers\Behavior;
use yubundle\account\domain\v2\forms\restorePassword\UpdatePasswordForm;
use Yii;
use yii\web\Controller;
use yii2bundle\navigation\domain\widgets\Alert;

/**
 * PasswordController controller
 */
class RestorePasswordController extends Controller
{
	
	const SESSION_KEY = 'restore-password';
	
	public $defaultAction = 'request';
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => Behavior::access('?'),
		];
	}
	
	public function actionRequest() {
        $person = Yii::$app->session->get(self::SESSION_KEY);
        if(!empty($person)) {
            return $this->redirect('/user/restore-password/verify-code');
        }
        $model = new UpdatePasswordForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            try {
	            App::$domain->account->restorePassword->requestCode($model);
	            Yii::$app->session->set(self::SESSION_KEY, $model->toArray());
	            return $this->redirect('/user/restore-password/verify-code');
            } catch(NotFoundHttpException $e) {
	            $model->addError('phone', $e->getMessage());
            }
        }
        return $this->render('request', [
            'model' => $model,
        ]);
	}

    public function actionVerifyCode() {
        $person = Yii::$app->session->get(self::SESSION_KEY);
        if(empty($person)) {
            return $this->redirect('/user/restore-password');
        }
        if(!empty($person['activation_code'])) {
            return $this->redirect('/user/restore-password/update-password');
        }
        $model = new UpdatePasswordForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
	        $model->phone = $person['phone'];
	        try {
		        App::$domain->account->restorePassword->verifyCode($model);
		        $person['activation_code'] = $model->activation_code;
		        Yii::$app->session->set(self::SESSION_KEY, $person);
		        return $this->redirect('/user/restore-password/update-password');
	        } catch(NotFoundHttpException $e) {
		        Yii::$app->session->remove(self::SESSION_KEY);
		        return $this->redirect('/user/restore-password');
	        } catch(ConfirmIncorrectCodeException|ConfirmAttemptException $e) {
		        $model->addError('activation_code', $e->getMessage());
	        }
        }
        return $this->render('verify-code', [
            'model' => $model,
            'person' => $person,
        ]);
    }

    public function actionUpdatePassword() {
        $person = Yii::$app->session->get(self::SESSION_KEY);
        if(empty($person)) {
            return $this->redirect('/user/restore-password');
        }
        if(empty($person['activation_code'])) {
            return $this->redirect('/user/verify-code');
        }
        $model = new UpdatePasswordForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
	        $model->phone = $person['phone'];
	        $model->activation_code = $person['activation_code'];
	        try {
		        App::$domain->account->restorePassword->setNewPassword($model);
		        Yii::$app->session->remove(self::SESSION_KEY);
		        \App::$domain->navigation->alert->create(['user/restore-password', 'restore_password_success'], Alert::TYPE_SUCCESS);
		        return $this->goHome();
	        } catch(UnprocessableEntityHttpException $e) {}
        }
        return $this->render('update-password', [
            'model' => $model,
            'person' => $person,
        ]);
    }

}
