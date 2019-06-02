<?php

namespace yii2rails\extension\console\handlers;

use yii\helpers\Console;
use yii2rails\extension\console\helpers\Output;

class RenderHahdler {

    public function line($text) {
        Output::line($text);
    }

}
