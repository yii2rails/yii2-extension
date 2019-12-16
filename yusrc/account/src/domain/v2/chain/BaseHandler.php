<?php

namespace yubundle\account\domain\v2\chain;

use yubundle\account\domain\v2\chain\Handler;

class BaseHandler implements Handler  {

    private $handler;

    //TODO: переименовать
    public function setNextHandler($handler) {
        $this->handler = $handler;
    }

    public function get($request) {

    }

    public function nextHandle($request) {
        if (!is_null($this->handler)) {
            $this->handler->get($request);
        }
    }

}
