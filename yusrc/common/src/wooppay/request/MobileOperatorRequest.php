<?php

namespace yubundle\common\wooppay\request;

class MobileOperatorRequest extends Request
{

    public $phone;

    public function rules()
    {
        return [];
    }
}
