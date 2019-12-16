<?php

namespace yubundle\account\domain\v2\helpers;

use Workerman\Connection\ConnectionInterface;
use yubundle\account\domain\v2\entities\SocketConnectEntity;

class SocketConnection {

    private $connections = [];

    public function get($key) {
        return $this->connections[$key];
    }

    public function set(SocketConnectEntity $socketConnectEntity) {
        $socketConnectEntity->validate();
        $this->connections[$socketConnectEntity->user_id][] = $socketConnectEntity;
    }

    public function has($key) : bool {
        return array_key_exists($key, $this->connections);
    }

    public function remove($key) {
        unset($this->connections[$key]);
    }

    public function getByConnection(ConnectionInterface $connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $socketConnectCollection) {
            foreach ($socketConnectCollection as $key => $socketConnectEntity) {
                if($socketConnectEntity->connection == $connection) {
                    return $socketConnectEntity;
                }
            }
        }
    }

    public function hasByConnection(ConnectionInterface $connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $socketConnectCollection) {
            foreach ($socketConnectCollection as $key => $socketConnectEntity) {
                if($socketConnectEntity->connection == $connection) {
                    return true;
                }
            }
        }
        return false;
    }

    public function removeByConnection(ConnectionInterface $connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $socketConnectCollection) {
            foreach ($socketConnectCollection as $key => $socketConnectEntity) {
                if($socketConnectEntity->connection == $connection) {
                    $this->remove($key);
                }
            }
        }
    }

}
