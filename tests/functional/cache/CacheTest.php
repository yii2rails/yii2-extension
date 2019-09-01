<?php

namespace tests\functional\container;

use yii2rails\extension\cache\Cache;
use yii2rails\extension\cache\CacheItem;
use yii2rails\extension\container\Container;
use yii2rails\extension\encrypt\helpers\JwtService;
use yii2tool\test\Test\Unit;

class CacheTest extends Unit {
	
	const PACKAGE = 'yii2rails/yii2-extension';

    public function testClear() {
        $cache = new Cache;
        $cache->deleteItem('key1');
        $this->tester->assertEquals(null, $cache->getItem('key1')->get());
        //$cache->deleteItems(['key1']);
        //$this->tester->assertEquals([], $cache->getItems(['key1']));
    }

    public function testSet() {
        $cache = new Cache;
        $item = new CacheItem('key1', 'val1');
        $this->tester->assertEquals(false, $cache->hasItem('key1'));
        $cache->save($item);
        $this->tester->assertEquals('val1', $cache->getItem('key1')->get());
        $this->tester->assertEquals(true, $cache->hasItem('key1'));
    }

    public function testDelete() {
        $cache = new Cache;
        $item = new CacheItem('key1', 'val1');
        $cache->save($item);
        $this->tester->assertEquals('val1', $cache->getItem('key1')->get());
        $cache->deleteItem('key1');
        $this->tester->assertEquals(null, $cache->getItem('key1')->get());
    }

    public function testSetExpire() {
        $cache = new Cache;
        $time = new \DateTime;

        $item1 = new CacheItem('key1', 'val1');
        $time->setTimestamp(time() + 1);
        $item1->expiresAt($time);
        $this->tester->assertEquals(false, $cache->hasItem('key1'));
        $cache->save($item1);
        $this->tester->assertEquals('val1', $cache->getItem('key1')->get());
        $this->tester->assertEquals(true, $cache->hasItem('key1'));

        $item2 = new CacheItem('key2', 'val2');
        $item2->expiresAfter(1);
        $this->tester->assertEquals(false, $cache->hasItem('key2'));
        $cache->save($item2);
        $this->tester->assertEquals('val2', $cache->getItem('key2')->get());
        $this->tester->assertEquals(true, $cache->hasItem('key2'));

        $item3 = new CacheItem('key3', 'val3', 1);
        $this->tester->assertEquals(false, $cache->hasItem('key3'));
        $cache->save($item3);
        $this->tester->assertEquals('val3', $cache->getItem('key3')->get());
        $this->tester->assertEquals(true, $cache->hasItem('key3'));

        sleep(1);

        $this->tester->assertEquals(null, $cache->getItem('key1')->get());
        $this->tester->assertEquals(false, $cache->hasItem('key1'));

        $this->tester->assertEquals(null, $cache->getItem('key2')->get());
        $this->tester->assertEquals(false, $cache->hasItem('key2'));

        $this->tester->assertEquals(null, $cache->getItem('key3')->get());
        $this->tester->assertEquals(false, $cache->hasItem('key3'));
    }

}
