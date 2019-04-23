<?php

namespace yii2rails\extension\telegram\repositories\mock;

use yii2rails\extension\telegram\helpers\MockResponseHelper;
use yii2rails\extension\telegram\interfaces\repositories\ResponseInterface;
use yii2rails\extension\telegram\libs\AppLib;
use TelegramBot\Api\Types\Message;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class ResponseRepository
 * 
 * @package yii2rails\extension\telegram\repositories\mock
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
        MockResponseHelper::insert('sendMessage', $message, [
            'answerText' => $answerText,
        ]);
    }

    public function sendKeyboard(Message $message, $answerText, $keys) {
        MockResponseHelper::insert('sendKeyboard', $message, [
            'answerText' => $answerText,
            'keys' => $keys,
        ]);
    }

    public function sendImage(Message $message, $photo, $caption = null, $replyToMessageId = null, $replyMarkup = null, $disableNotification = false, $parseMode = null) {
        MockResponseHelper::insert('sendKeyboard', $message, [
            'photo' => $photo,
            'caption' => $caption,
            'replyToMessageId' => $replyToMessageId,
            'replyMarkup' => $replyMarkup,
            'disableNotification' => $disableNotification,
            'parseMode' => $parseMode,
        ]);
    }

}
