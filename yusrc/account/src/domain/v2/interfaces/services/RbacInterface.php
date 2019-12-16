<?php

namespace yubundle\account\domain\v2\interfaces\services;

interface RbacInterface {

	public function can($rule, $param = null, $allowCaching = true);

}