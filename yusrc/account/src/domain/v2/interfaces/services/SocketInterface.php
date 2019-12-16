<?php

namespace yubundle\account\domain\v2\interfaces\services;

use yii\base\ErrorException;
use yubundle\account\domain\v2\entities\SocketEventEntity;

/**
 * Interface SocketInterface
 * 
 * @package yubundle\account\domain\v2\interfaces\services
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
interface SocketInterface {

    /**
     * @param SocketEventEntity $event
     * @return mixed
     * @throws ErrorException
     */
    public function sendMessage(SocketEventEntity $event);
    public function startServer();
	
}
