<?php

namespace yii2rails\extension\telegram\interfaces\repositories;

use yii2rails\extension\telegram\libs\AppLib;
use TelegramBot\Api\Types\Message;

/**
 * Interface ResponseInterface
 * 
 * @package yii2rails\extension\telegram\interfaces\repositories
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 * @property $columns
 */
interface ResponseInterface {

    public function setApp(AppLib $app);
    public function sendMessage(Message $message, $answerText);
    public function sendKeyboard(Message $message, $answerText, $keys);
    public function sendImage(Message $message, $photo, $caption = null, $replyToMessageId = null, $replyMarkup = null, $disableNotification = false, $parseMode = null);

}
