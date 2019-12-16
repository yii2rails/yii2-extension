<?php

namespace yubundle\account\domain\v2\repositories\base;

use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\entities\SecurityEntity;
use yubundle\account\domain\v2\helpers\TokenHelper;
use yubundle\account\domain\v2\interfaces\repositories\AuthInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class BaseAuthRepository
 *
 * @package yubundle\account\domain\v2\repositories\base
 * @property \yubundle\account\domain\v2\Domain $domain
 */
class BaseAuthRepository extends BaseRepository implements AuthInterface {
	
	public function authentication($login, $password, $ip = null) {
		try {
            $query = new Query;
			$query->with('assignments');
			$query->with('person');
            if(\App::$domain->has('staff')) {
	            $query->with('company');
            }
			/** @var LoginEntity $loginEntity */
			$loginEntity = $this->domain->repositories->login->oneByVirtual($login, $query);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if(empty($loginEntity)) {
			return false;
		}
		/** @var SecurityEntity $securityEntity */
		try {
			$securityEntity = $this->domain->repositories->security->validatePassword($loginEntity->id, $password);
		} catch(UnprocessableEntityHttpException $e) {
			return false;
		}
		$securityEntity->token = $this->domain->token->forge($loginEntity->id, $ip);
		$loginEntity->security = $securityEntity;
		return $loginEntity;
	}

    public function authenticationByToken($token, $type = null, $ip = null) {
        try {
            $loginEntity = TokenHelper::authByToken($token, $this->domain->auth->tokenAuthMethods);
            //AuthHelper::setToken($loginEntity->token);
        } catch(NotFoundHttpException $e) {
            throw new UnauthorizedHttpException();
        }
        /*$query = new Query;
        $query->with(['assignments', 'person', 'company']);
        $loginEntity = \App::$domain->account->login->oneById($loginEntity->id, $query);*/
        return $loginEntity;
    }

}