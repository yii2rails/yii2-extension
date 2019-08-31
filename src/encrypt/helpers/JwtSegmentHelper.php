<?php

namespace yii2rails\extension\encrypt\helpers;

class JwtSegmentHelper {

    public static function encodeSegment($data) {
        return Base64Helper::urlSafeEncode(JwtJsonHelper::encode($data));
    }

    public static function decodeSegment($data) {
        return JwtJsonHelper::decode(Base64Helper::urlSafeDecode($data));
    }

}
