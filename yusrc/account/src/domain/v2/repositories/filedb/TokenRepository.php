<?php

namespace yubundle\account\domain\v2\repositories\filedb;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yubundle\account\domain\v2\entities\TokenEntity;
use yubundle\account\domain\v2\interfaces\repositories\TokenInterface;

/**
 * Class TokenRepository
 * 
 * @package yubundle\account\domain\v2\repositories\ar
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
class TokenRepository extends BaseRepository implements TokenInterface {
	
	
	/**
	 * @param $token
	 *
	 * @return TokenEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneByToken($token) {
		// TODO: Implement oneByToken() method.
	}
	
	/**
	 * @param $ip
	 *
	 * @return TokenEntity[]
	 */
	public function allByIp($ip) {
		// TODO: Implement allByIp() method.
	}
	
	public function allByUserId($userId) {
		// TODO: Implement allByUserId() method.
	}
	
	public function deleteOneByToken($token) {
		// TODO: Implement deleteOneByToken() method.
	}
	
	public function deleteAllExpired() {
		// TODO: Implement deleteAllExpired() method.
	}
}
