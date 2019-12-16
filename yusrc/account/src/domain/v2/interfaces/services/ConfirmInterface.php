<?php

namespace yubundle\account\domain\v2\interfaces\services;

use yii\web\NotFoundHttpException;
use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\account\domain\v2\exceptions\ConfirmAlreadyExistsException;
use yubundle\account\domain\v2\exceptions\ConfirmIncorrectCodeException;

interface ConfirmInterface extends CrudInterface {
	
	public function delete($login, $action);
	
	public function activate($login, $action, $code);
	
	public function isActivated($login, $action);
	
	/**
	 * @param $login
	 * @param $action
	 * @param $code
	 *
	 * @return bool
	 *
	 * @throws NotFoundHttpException
	 */
	public function isVerifyCode($login, $action, $code);
	
	/**
	 * @param $login
	 * @param $action
	 * @param $code
	 *
	 * @return mixed
	 * @throws ConfirmIncorrectCodeException|NotFoundHttpException
	 */
	public function verifyCode($login, $action, $code);
	
	public function isHas($login, $action);
	
	//public function oneByLoginAndAction($login, $action);
	
	/**
	 * @throws ConfirmAlreadyExistsException
	 */
	public function send($login, $action, $expire, $data = null);

}