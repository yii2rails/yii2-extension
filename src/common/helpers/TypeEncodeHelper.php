<?php

namespace yii2rails\extension\common\helpers;

class TypeEncodeHelper {
	
	public static function bool($value) {
        if($value == 'true' || $value == 'on') {
            return true;
        }
        if($value == 'false' || $value == 'off') {
            return false;
        }
        return boolval($value);
    }
	
}
