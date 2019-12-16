<?php

namespace yubundle\account\domain\v2\filters\token;

use yii\web\IdentityInterface;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\entities\LoginEntity;

class DefaultFilter extends BaseTokenFilter {
	
	public function authByToken($token) {
	    $query = new Query;
	    $query->with('assignments');
		$loginEntity = \App::$domain->account->repositories->login->oneByToken($token, $query);
		return $loginEntity;
	}
	
	public function login($body, $ip) {
		$loginEntity = \App::$domain->account->repositories->auth->authentication($body['login'], $body['password'], $ip);
		if($loginEntity instanceof IdentityInterface) {
            $loginEntity->token = $this->forgeToken($loginEntity->token);
            return $loginEntity;
        }
		return null;
	}
	
}
