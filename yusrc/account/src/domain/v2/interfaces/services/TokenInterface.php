<?php

namespace yubundle\account\domain\v2\interfaces\services;

use yubundle\account\domain\v2\entities\TokenEntity;

/**
 * Interface TokenInterface
 * 
 * @package yubundle\account\domain\v2\interfaces\services
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 * @property-read \yubundle\account\domain\v2\interfaces\repositories\TokenInterface $repository
 */
interface TokenInterface {

    /**
     * @param integer $userId
     * @param string $ip
     * @param null $expire
     * @return string
     */
    public function forge($userId, $ip, $expire = null);
	
	/**
	 * @param $token
	 * @param $ip
	 *
	 * @return null|TokenEntity
	 */
	public function validate($token, $ip);
	
}
