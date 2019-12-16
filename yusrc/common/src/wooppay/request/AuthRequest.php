<?php

namespace yubundle\common\wooppay\request;

class AuthRequest extends Request
{

    public $username;
    public $password;
    public $captcha = null;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
        ];
    }
}
