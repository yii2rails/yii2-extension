<?php

namespace yubundle\account\domain\v2\enums;

use yii2rails\extension\enum\base\BaseEnum;

class RegistrationAccessEnum extends BaseEnum
{

    const OPEN = 'open';
    const INVITE = 'invite';
    const CLOSE = 'close';

}