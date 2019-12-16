<?php

namespace yubundle\account\domain\v2\services;

use yii2rails\domain\services\base\BaseActiveService;
use yubundle\account\domain\v2\interfaces\services\TestInterface;

/**
 * Class TestService
 *
 * @package yubundle\account\domain\v2\services
 * @property \yubundle\account\domain\v2\interfaces\repositories\TestInterface $repository
 */
class TestService extends BaseActiveService implements TestInterface {

	public function getOneByRole($role) {
		$user = $this->repository->getOneByRole($role);
		return $user;
	}

	public function oneByLogin($login) {
		$user = $this->repository->oneByLogin($login);
		return $user;
	}

}
