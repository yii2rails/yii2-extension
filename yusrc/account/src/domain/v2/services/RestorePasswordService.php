<?php

namespace yubundle\account\domain\v2\services;

use App;
use Yii;
use yii\web\NotFoundHttpException;
use yubundle\account\domain\v2\enums\AccountConfirmActionEnum;
use yubundle\account\domain\v2\exceptions\ConfirmAlreadyExistsException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2rails\extension\common\exceptions\CreatedHttpExceptionException;
use yubundle\account\domain\v2\forms\LoginForm;
use yubundle\account\domain\v2\forms\restorePassword\UpdatePasswordForm;
use yubundle\account\domain\v2\interfaces\services\RestorePasswordInterface;
use yii2rails\domain\services\base\BaseService;
use yii2rails\extension\enum\enums\TimeEnum;

/**
 * Class RestorePasswordService
 *
 * @package yubundle\account\domain\v2\services
 *
 * @property-read \yubundle\account\domain\v2\interfaces\repositories\RestorePasswordInterface $repository
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
class RestorePasswordService extends BaseService implements RestorePasswordInterface {
	
	public $smsCodeExpire = TimeEnum::SECOND_PER_MINUTE * 30;
	
	public function requestCode(UpdatePasswordForm $model) {
		$model->scenario = UpdatePasswordForm::SCENARIO_REQUEST_CODE;
		if(!$model->validate()) {
			throw new UnprocessableEntityHttpException($model);
		}
		if(!App::$domain->user->person->isExistsByPhone($model->phone)) {
			throw new NotFoundHttpException(Yii::t('user/account', 'not_found'));
		}
		try {
			App::$domain->account->confirm->send($model->phone, AccountConfirmActionEnum::RESTORE_PASSWORD, $this->smsCodeExpire);
		} catch(ConfirmAlreadyExistsException $e) {
			//throw new CreatedHttpExceptionException(Yii::t('account/registration', 'user_already_exists_but_not_activation'));
            throw new CreatedHttpExceptionException(Yii::t('account/restore-password', 'sms_code_for_reset_password_expire'));
		}
	}
	
	public function verifyCode(UpdatePasswordForm $model) {
		$model->scenario = UpdatePasswordForm::SCENARIO_VERIFY_CODE;
		if(!$model->validate()) {
			throw new UnprocessableEntityHttpException($model);
		}
		App::$domain->account->confirm->verifyCode($model->phone, AccountConfirmActionEnum::RESTORE_PASSWORD, $model->activation_code);
	}
	
	public function setNewPassword(UpdatePasswordForm $model) {
		$model->scenario = UpdatePasswordForm::SCENARIO_SET_PASSWORD;
		if(!$model->validate()) {
			throw new UnprocessableEntityHttpException($model);
		}
		$this->verifyCode($model);
		if($this->isOldPassword($model)) {
			$model->addError('password', Yii::t('account/restore-password', 'old_password_message'));
			throw new UnprocessableEntityHttpException($model);
		}
		$this->repository->setNewPassword($model->phone, null, $model->password);
	}
	
	private function isOldPassword(UpdatePasswordForm $model) {
		$loginForm = new LoginForm;
		$loginForm->login = $model->phone;
		$loginForm->password = $model->password;
		try {
			App::$domain->account->auth->authenticationFromApi($loginForm);
			return true;
		} catch(UnprocessableEntityHttpException $e) {
			return false;
		}
	}
	
}
