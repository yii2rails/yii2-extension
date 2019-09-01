<?php

namespace yii2rails\extension\encrypt\helpers;

class JwtSegmentHelper {

    public static function encodeSegment($data) {
        return SafeBase64Helper::encode(JwtJsonHelper::encode($data));
    }

    public static function decodeSegment($data) {
        return JwtJsonHelper::decode(SafeBase64Helper::decode($data));
    }

}
