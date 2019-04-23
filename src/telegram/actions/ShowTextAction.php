<?php

namespace yii2rails\extension\telegram\actions;

use TelegramBot\Api\Types\Message;

class ShowTextAction extends BaseAction {
	
	public $text;
	
	public function run(Message $message) {
        $this->app->response->sendMessage($message, $this->text);
        //$this->app->response->showKeyboard($message, $this->text, ['111','222']);
        /*$this->app->response->showImage(
            $message,
            'https://mobimg.b-cdn.net/pic/v2/gallery/preview/oshki_oty_otiki-zhivotnye-44734.jpg',
            'qwert');*/
	}
	
}
