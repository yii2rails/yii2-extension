<?php

namespace yii2rails\extension\telegram\actions;

use common\handlers\BaseHandler;
use TelegramBot\Api\Types\Message;

abstract class BaseAction extends BaseHandler {

    abstract public function run(Message $message);

}
