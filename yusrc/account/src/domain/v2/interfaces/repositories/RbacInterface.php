<?php

namespace yubundle\account\domain\v2\interfaces\repositories;

interface RbacInterface {
	
	public function isGuestOnlyAllowed($rule);
	public function isAuthOnlyAllowed($rule);

}