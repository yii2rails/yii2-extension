<?php

namespace yubundle\account\domain\v2\events;

use yii\base\Event;

class AccountAuthenticationEvent extends Event
{

    public $identity;
    public $login;

}