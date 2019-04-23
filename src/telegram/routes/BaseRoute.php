<?php

namespace yii2rails\extension\telegram\routes;

use TelegramBot\Api\Types\Message;
use yii2rails\extension\common\helpers\ClassHelper;
use common\handlers\BaseHandler;

abstract class BaseRoute extends BaseHandler {
	
	public $exp;
    public $params;
	public $handler;

    public function run(Message $message) {
        $handlerInstance = ClassHelper::createObject($this->handler, [$this->app]);
        $handlerInstance->run($message);
    }

    abstract function isMatch(Message $message);

}
