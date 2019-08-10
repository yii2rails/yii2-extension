<?php

namespace yii2rails\extension\encrypt\enums;

use yii2rails\extension\enum\base\BaseEnum;

class EncryptFunctionEnum extends BaseEnum {

	const HASH_HMAC = 'hash_hmac';
	const OPENSSL = 'openssl';

}
