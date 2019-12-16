<?php

namespace yubundle\account\domain\v2\enums;

use yii2rails\extension\enum\base\BaseEnum;

class AccountConfirmActionEnum extends BaseEnum
{

    const REGISTRATION = 'registration';
	const RESTORE_PASSWORD = 'restore-password';

}