<?php

namespace yubundle\account\domain\v2\dto;

use yii2rails\domain\base\BaseDto;

/**
 * Class TokenDto
 *
 * @package yubundle\account\domain\v2\dto
 *
 * @property $token
 * @property $type
 */
class TokenDto extends BaseDto {
	
	public $token;
	public $type;
	
	public function getTokenString() {
		if(empty($this->token)) {
			return null;
		}
		if(empty($this->type)) {
			return $this->token;
		}
		return $this->type . SPC . $this->token;
	}
	
}
