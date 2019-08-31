<?php

namespace yii2rails\extension\encrypt\helpers;

class Base64Helper {

    public static function urlSafeDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function urlSafeEncode($input) : string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

}
