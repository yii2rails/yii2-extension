<?php

namespace yubundle\account\domain\v2\interfaces\services;

use yii\web\NotFoundHttpException;
use yubundle\account\domain\v2\exceptions\ConfirmIncorrectCodeException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\common\exceptions\CreatedHttpExceptionException;
use yubundle\account\domain\v2\forms\restorePassword\UpdatePasswordForm;

/**
 * Interface RestorePasswordInterface
 * 
 * @package yubundle\account\domain\v2\interfaces\services
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
interface RestorePasswordInterface {
	
	/**
	 * @param UpdatePasswordForm $model
	 *
	 * @throws CreatedHttpExceptionException
	 * @throws NotFoundHttpException
	 * @throws UnprocessableEntityHttpException
	 */
	public function requestCode(UpdatePasswordForm $model);
	
	/**
	 * @param UpdatePasswordForm $model
	 *
	 * @throws ConfirmIncorrectCodeException
	 * @throws NotFoundHttpException
	 * @throws UnprocessableEntityHttpException
	 */
	public function verifyCode(UpdatePasswordForm $model);
	
	/**
	 * @param UpdatePasswordForm $model
	 * @throws UnprocessableEntityHttpException
	 */
	public function setNewPassword(UpdatePasswordForm $model);
	
}
