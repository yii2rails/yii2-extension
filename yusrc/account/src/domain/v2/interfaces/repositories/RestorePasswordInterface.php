<?php

namespace yubundle\account\domain\v2\interfaces\repositories;

use yii\web\NotFoundHttpException;

/**
 * Class RestorePasswordRepository
 *
 * @package yubundle\account\domain\v2\repositories\ar
 *
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
interface RestorePasswordInterface {
	
	public function requestNewPassword($login, $mail = null);
	
	/**
	 * @param $login
	 * @param $code
	 *
	 * @return bool
	 *
	 * @throws NotFoundHttpException
	 */
	
	public function checkActivationCode($login, $code);
	public function setNewPassword($login, $code, $password);

}