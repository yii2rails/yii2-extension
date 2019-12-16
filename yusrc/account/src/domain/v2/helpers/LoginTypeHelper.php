<?php

namespace yubundle\account\domain\v2\helpers;

use yii2rails\extension\common\enums\RegexpPatternEnum;

class LoginTypeHelper  {

	public static function isPhone($login) {
		return preg_match('#^[0-9]{9,}$#i', $login);
	}

    public static function isEmail($login) {
        return preg_match(RegexpPatternEnum::EMAIL_REQUIRED, $login);
    }

}
