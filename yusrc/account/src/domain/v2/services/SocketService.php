<?php

namespace yubundle\account\domain\v2\services;

use Workerman\Connection\ConnectionInterface;
use yii\web\UnauthorizedHttpException;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\console\helpers\Output;
use yii2rails\extension\registry\helpers\Registry;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\entities\SocketEventEntity;
use yubundle\account\domain\v2\forms\LoginForm;
use yubundle\account\domain\v2\helpers\Socket;
use yubundle\account\domain\v2\interfaces\services\SocketInterface;
use yii2rails\domain\services\base\BaseService;
use Workerman\Worker;
use App;

class SocketService extends BaseService implements SocketInterface {

    private $socket;

    public function sendMessage(SocketEventEntity $event) {
        $socket = $this->getInstance();
        $socket->sendMessage($event);
    }

    public function startServer() {
        $socket = $this->getInstance();
        $socket->start();
    }

    private function getInstance() : Socket {
        if(empty($this->socket)) {
            $socketConfig = EnvService::getServer('socket');
            $this->socket = new Socket($socketConfig);
        }
        return $this->socket;
    }
}
