<?php

namespace yii2rails\extension\cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class Cache implements CacheItemPoolInterface {

    public function getItem($key) : CacheItemInterface {
        $value = \Yii::$app->cache->get($key);
        return new CacheItem($key, $value);
    }

    public function getItems(array $keys = array()) : array {
        return \Yii::$app->cache->multiGet($keys);
    }

    public function hasItem($key) : bool {
        return \Yii::$app->cache->exists($key);
    }

    public function clear() : bool {
        return \Yii::$app->cache->flush();
    }

    public function deleteItem($key) : bool {
        return \Yii::$app->cache->delete($key);
    }

    public function deleteItems(array $keys) : bool {
        $result = true;
        foreach ($keys as $key) {
            $res = $this->deleteItem($key);
            if(!$res) {
                $result = false;
            }
        }
        return $result;
    }

    public function save(CacheItemInterface $item) : bool {
        /** @var \DateTime $expire */
        /** @var CacheItem $item */
        $expire = $item->getExpire();
        $duration = $expire ? $expire->getTimestamp() - time() : null;
        return \Yii::$app->cache->set($item->getKey(), $item->get(), $duration);
    }

    public function saveDeferred(CacheItemInterface $item) : bool {

    }

    public function commit() : bool {

    }

}
