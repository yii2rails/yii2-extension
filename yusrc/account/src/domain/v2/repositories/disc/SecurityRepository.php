<?php

namespace yubundle\account\domain\v2\repositories\filedb;

use yii2rails\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yubundle\account\domain\v2\interfaces\repositories\SecurityInterface;

class SecurityRepository extends BaseActiveDiscRepository implements SecurityInterface {
	
	public $table = 'user_security';
	
	public function changePassword($password, $newPassword) {
		// TODO: Implement changePassword() method.
	}
	
	public function changeEmail($password, $email) {
		// TODO: Implement changeEmail() method.
	}
	
	public function generateUniqueToken() {
		// TODO: Implement generateUniqueToken() method.
	}
}