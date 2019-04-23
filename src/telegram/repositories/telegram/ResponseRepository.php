<?php

namespace yii2rails\extension\telegram\repositories\telegram;

use yii2rails\extension\telegram\helpers\MenuHelper;
use yii2rails\extension\telegram\interfaces\repositories\ResponseInterface;
use yii2rails\extension\telegram\libs\AppLib;
use TelegramBot\Api\Types\Message;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class ResponseRepository
 * 
 * @package yii2rails\extension\telegram\repositories\telegram
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 */
class ResponseRepository extends BaseRepository implements ResponseInterface {

    /**
     * @var AppLib
     */
    protected $app;
    public $columns = 3;

    public function setApp(AppLib $app) {
        $this->app = $app;
    }

    public function sendMessage(Message $message, $answerText) {
        $cid = $message->getChat()->getId();
        return $this->app->bot->sendMessage($cid, $answerText);
    }

    public function sendKeyboard(Message $message, $answerText, $keys) {
        $cid = $message->getChat()->getId();
        $keyboard = MenuHelper::createKeyboard($keys);
        return $this->app->bot->sendMessage($cid, $answerText, false, null,null, $keyboard);
    }

    public function sendImage(Message $message, $photo, $caption = null, $replyToMessageId = null, $replyMarkup = null, $disableNotification = false, $parseMode = null) {
        $cid = $message->getChat()->getId();
        return $this->app->bot->sendPhoto($cid, $photo, $caption, $replyToMessageId, $replyMarkup, $disableNotification, $parseMode);
    }

}
