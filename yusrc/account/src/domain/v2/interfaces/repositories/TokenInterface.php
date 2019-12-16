<?php

namespace yubundle\account\domain\v2\interfaces\repositories;

use yii2rails\domain\interfaces\repositories\CrudInterface;
use yubundle\account\domain\v2\entities\TokenEntity;

/**
 * Interface TokenInterface
 * 
 * @package yubundle\account\domain\v2\interfaces\repositories
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
interface TokenInterface extends CrudInterface {
	
	/**
	 * @param $token
	 *
	 * @return TokenEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneByToken($token);
	
	/**
	 * @param $ip
	 *
	 * @return TokenEntity[]
	 */
	public function allByIp($ip);
	public function allByUserId($userId);
	//public function deleteByIp($ip);
	public function deleteOneByToken($token);
	//public function deleteAllExpiredByIp($ip);
	public function deleteAllExpired();
	
}
