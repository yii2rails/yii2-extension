<?php

namespace yubundle\account\domain\v2\repositories\core;

use yii2rails\extension\core\domain\repositories\base\BaseActiveCoreRepository;
use yubundle\account\domain\v2\interfaces\repositories\SecurityInterface;

class SecurityRepository extends BaseActiveCoreRepository implements SecurityInterface {
	
	public $point = 'security';
	
	public function changePassword($password, $newPassword) {
		$response = $this->put('password', [
			'password' => $password,
			'new_password' => $newPassword,
		]);
	}
	
	public function changeEmail($password, $email) {
		$response = $this->put('email', [
			'password' => $password,
			'email' => $email,
		]);
	}
	
}