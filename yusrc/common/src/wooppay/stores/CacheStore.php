<?php

namespace yubundle\common\wooppay\stores;

class CacheStore implements StoreInterface
{

    private $keyPrefix = 'store2';

    public function get($key) {
        $key = $this->key($key);
        return \Yii::$app->cache->get($key);
    }

    public function set($key, $value, $duration = null) {
        $key = $this->key($key);
        \Yii::$app->cache->set($key, $value, $duration);
    }

    public function delete($key) {
        $key = $this->key($key);
        \Yii::$app->cache->delete($key);
    }

    private function key($key) {
        return $this->keyPrefix . '_' . $key;
    }
}
