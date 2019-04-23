<?php

namespace yii2rails\extension\telegram\actions;

use TelegramBot\Api\Types\Message;

class UnknownAction extends BaseAction {
	
	public $text = 'я не поняла, что ты сказал';
    public $keyboard = ['добавить выражение', 'добавить меню'];
	
	public function run(Message $message) {
        $expression = $message->getText();
        $this->app->setSession('dialog.expression', $expression);
        $this->app->response->sendKeyboard($message, $this->text, $this->keyboard);
	}
	
}
