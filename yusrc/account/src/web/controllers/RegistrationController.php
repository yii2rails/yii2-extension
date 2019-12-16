<?php

namespace yubundle\account\web\controllers;

use yii\web\NotFoundHttpException;
use yubundle\account\domain\v2\exceptions\ConfirmAttemptException;
use yubundle\account\domain\v2\exceptions\ConfirmIncorrectCodeException;
use yubundle\account\domain\v2\forms\LoginForm;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2rails\extension\common\exceptions\CreatedHttpExceptionException;
use yii2rails\extension\web\helpers\Behavior;
use yubundle\account\domain\v2\forms\registration\PersonInfoForm;
use Yii;
use yii\web\Controller;
use yii2bundle\navigation\domain\widgets\Alert;

class RegistrationController extends Controller {

    const SESSION_KEY = 'registration/person-info';

	public $defaultAction = 'person-info';

	public function behaviors() {
		return [
			'access' => Behavior::access('?'),
		];
	}

	public function actionPersonInfo() {
        $person = Yii::$app->session->get(self::SESSION_KEY);
        if(!empty($person['phone'])) {
            return $this->redirect('/user/registration/verify-code');
        }
        $model = new PersonInfoForm;
		Helper::forgeForm($model, $person);
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            try {
                \App::$domain->account->registration->requestCodeWithPersonInfo($model);
                Yii::$app->session->set(self::SESSION_KEY, $model->toArray());
                return $this->redirect('/user/registration/verify-code');
            } catch (UnprocessableEntityHttpException $e) {
	            $model->addErrors($e->getErrorsForModel());
            } catch (CreatedHttpExceptionException $e) {
	            $model->addError('phone', $e->getMessage());
            } catch (AlreadyExistsException $e) {
	            $model->addError('phone', $e->getMessage());
            }
        }
        return $this->render('person-info', [
            'model' => $model,
        ]);
	}
	
	public function actionVerifyCode() {
        $person = Yii::$app->session->get(self::SESSION_KEY);
        if(empty($person['phone'])) {
            return $this->redirect('/user/registration');
        }
        $model = new PersonInfoForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            $model->phone = $person['phone'];
            try {
                \App::$domain->account->registration->verifyCode($model);
                $personForm = new PersonInfoForm;
	            $personForm->scenario = PersonInfoForm::SCENARIO_PERSON_INFO;
                $personForm->setAttributes($person, false);
                \App::$domain->account->registration->createAccountWeb($personForm);
                Yii::$app->session->remove(self::SESSION_KEY);
                $loginForm = new LoginForm;
	            $loginForm->login = $personForm->login;
	            $loginForm->password = $personForm->password;
	            $loginForm->rememberMe = true;
	            \App::$domain->account->auth->authenticationFromWeb($loginForm);
                \App::$domain->navigation->alert->create(['user/registration', 'registration_success'], Alert::TYPE_SUCCESS);
                return $this->goHome();
            } catch (UnprocessableEntityHttpException $e) {
	            $model->addErrors($e->getErrorsForModel());
            } catch (AlreadyExistsException $e) {
            	unset($person['phone']);
	            Yii::$app->session->set(self::SESSION_KEY, $person);
	            \App::$domain->navigation->alert->create($e->getMessage(), Alert::TYPE_WARNING);
	            return $this->redirect('/user/registration');
            } catch(NotFoundHttpException $e) {
	            $model->addError('phone', $e->getMessage());
            } catch(ConfirmIncorrectCodeException|ConfirmAttemptException $e) {
	            $model->addError('activation_code', $e->getMessage());
            }
        }
        return $this->render('verify-code', [
            'model' => $model,
            'person' => $person,
        ]);
    }

}
