<?php

namespace yubundle\account\domain\v2\interfaces;

interface LoginValidatorInterface {
	
	public function normalize($value) : string;
	public function isValid($value) : bool;
	
}
