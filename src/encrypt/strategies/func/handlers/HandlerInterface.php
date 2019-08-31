<?php

namespace yii2rails\extension\encrypt\strategies\func\handlers;

use yii2rails\domain\data\Query;
use yii2rails\extension\encrypt\dto\TokenDto;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;

interface HandlerInterface {

    public function sign($msg, $algorithm, $key);
    public function verify($msg, $algorithm, $key, $signature);
}
