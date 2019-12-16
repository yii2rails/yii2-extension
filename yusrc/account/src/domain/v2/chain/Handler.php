<?php

namespace yubundle\account\domain\v2\chain;

interface Handler {

    public function setNextHandler(BaseHandler $handler);
    public function get($request);

}
