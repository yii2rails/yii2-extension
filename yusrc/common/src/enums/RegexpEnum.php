<?php

namespace yubundle\common\enums;

use yii2rails\extension\enum\base\BaseEnum;

class RegexpEnum extends BaseEnum {
	
	const PASSWORD_REQUIRED = '/^[a-zA-Z0-9-_]+$/';

}
