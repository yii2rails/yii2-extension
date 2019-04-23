<?php

namespace yii2rails\extension\telegram\actions;

use TelegramBot\Api\Types\Message;
use yii2rails\extension\common\helpers\StringHelper;

class MemoryAction extends BaseAction {
	
	public $text;
	public $exp = 'запомни\s+(.+)\s*\-\s*(.+)';
	
	public function run(Message $message) {
        $expression = $this->app->getSession('dialog.expression');
        $expression = $this->normalizeExpression($expression);
	     if($this->app->getState() == 'default') {
             $this->app->setState('dialog.expression');
             $text = 'Введите ответ на фразу: "' . $expression . '""';
             //$this->app->response->sendMessage($message, $text);
             $this->app->response->sendKeyboard($message, $text, ['отмена']);
         } elseif ($this->app->getState() == 'dialog.expression') {
             $answer = $message->getText();
             $answer = StringHelper::removeDoubleSpace($answer);
             $answer = trim($answer);
             $expression = str_replace(SPC, '|', $expression);
             \App::$domain->telegram->route->create([
                 'bot_id' => $this->app->botId,
                 'class' => 'yii2rails\extension\telegram\routes\InArrayRoute',
                 'expression' => $expression,
                 'action_id' => 1,
                 'action_params_json' => '{"text": "' . $answer . '"}',
             ]);
             $this->app->response->sendMessage($message, '✅ запомнила');
             $this->app->clearState();
         }
	}

	private function normalizeExpression($expression) {
        $expression = StringHelper::removeDoubleSpace($expression);
        $expression = trim($expression);
        $expression = mb_strtolower($expression);
        return $expression;
    }

}
