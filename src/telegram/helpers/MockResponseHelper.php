<?php

namespace yii2rails\extension\telegram\helpers;

use TelegramBot\Api\Types\Message;
use yii2rails\extension\store\StoreFile;
use yii2rails\extension\yii\helpers\FileHelper;

class MockResponseHelper {

    const FILE = ROOT_DIR . SL . 'common/runtime/telegram/response.php';

    public static function first() {
        $storeFile = new StoreFile(self::FILE);
        $data = $storeFile->load();
        return $data[0];
    }

    public static function clear() {
        FileHelper::remove(self::FILE);
    }

    public static function insert($method, Message $message, $params) {
        $storeFile = new StoreFile(self::FILE);
        $data = $storeFile->load();
        if(empty($data)) {
            $data = [];
        }
        $data[] = [
            'method' => $method,
            //'message' => ArrayHelper::toArray(json_decode($message->toJson())),
            'params' => $params,
        ];
        $storeFile->save($data);
    }

}
