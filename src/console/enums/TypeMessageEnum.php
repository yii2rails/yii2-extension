<?php

namespace yii2rails\extension\console\enums;

use yii\helpers\Console;

class TypeMessageEnum {
	
    const INFO = 'info';
    const DANGER = 'danger';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const PRIMARY = 'primary';
    const DEFAULT = 'default';
    const CUSTOM = 'custom';

    public static function toArgs($type) {
        $types = [
            TypeMessageEnum::SUCCESS => Console::FG_GREEN,
            TypeMessageEnum::WARNING => Console::FG_YELLOW,
            TypeMessageEnum::DANGER => Console::FG_RED,
            TypeMessageEnum::INFO => Console::FG_BLUE,
        ];
        return $types[$type];
    }
}
