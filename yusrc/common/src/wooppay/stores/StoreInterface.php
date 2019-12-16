<?php

namespace yubundle\common\wooppay\stores;

interface StoreInterface
{

    public function get($key);
    public function set($key, $value, $duration = null);
    public function delete($key);

}
