<?php

namespace yubundle\account\domain\v2\interfaces\repositories;

use yubundle\account\domain\v2\entities\LoginEntity;

interface AuthInterface {
	
	/**
	 * @param string $login
	 * @param string $password
	 * @param null   $ip
	 *
	 * @return LoginEntity
	 */
	public function authentication($login, $password, $ip = null);

    public function authenticationByToken($token, $type = null, $ip = null);
}