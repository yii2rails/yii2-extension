<?php

namespace yubundle\account\domain\v2\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2rails\domain\interfaces\repositories\CrudInterface;

interface ConfirmInterface extends CrudInterface {
	
	/**
	 * @param $login
	 * @param $action
	 *
	 * @return mixed
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLoginAndAction($login, $action);
	
	/**
	 * @param $login
	 *
	 * @return mixed
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLogin($login);
    public function deleteByLoginAndAction($login, $action);
	public function cleanOld($login, $action);
	public function cleanAll($login, $action);

}