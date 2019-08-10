<?php

namespace yii2rails\extension\encrypt\helpers;

use yii2rails\extension\enum\base\BaseEnum;

class EncryptHelper {

    public static function safeStrlen($str)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }

}
