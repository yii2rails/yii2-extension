<?php

namespace yubundle\account\domain\v2\interfaces\services;

use yii\authclient\BaseOAuth;
use yii\web\IdentityInterface;
use yubundle\account\domain\v2\entities\LoginEntity;

/**
 * Interface OauthInterface
 * 
 * @package yubundle\account\domain\v2\interfaces\services
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
interface OauthInterface {
	
	public function isEnabled() : bool;
	public function oneById($id) : IdentityInterface;
	public function authByClient(BaseOAuth $client);
	
}
