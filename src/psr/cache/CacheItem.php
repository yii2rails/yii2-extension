<?php

namespace yii2rails\extension\psr\cache;

use Psr\Cache\CacheItemInterface;
use yii2rails\extension\common\enums\RegexpPatternEnum;

class CacheItem implements CacheItemInterface
{

    private $key;
    private $value;
    private $expire = null;
    private $isHit = false;

    public function __construct($key, $value, $duration = null)
    {
        self::assertKey($key);
        $this->key = $key;
        $this->value = $value;
        $this->expiresAfter($duration);
    }

    public function getKey() : string
    {
        return $this->key;
    }

    public function get()
    {
        return $this->value;
    }

    public function isHit() : bool
    {
        return $this->isHit;
    }

    public function set($value) : self
    {
        $this->value = $value;
        return $this;
    }

    public function expiresAt($expiration) : self
    {
        /** @var $expiration \DateTimeInterface */
        $this->expire = $expiration;
        return $this;
    }

    public function expiresAfter($time) : self
    {
        /** @var $time int|\DateInterval|null */
        if($time !== null) {
            if(is_integer($time)) {
                $time = \DateInterval::createFromDateString("+{$time} seconds");
            }
            $this->expire = new \DateTime();
            $this->expire->add($time);
        }
        return $this;
    }

    public function getExpire()
    {
        return $this->expire;
    }

    public static function assertKey($key) {
        if(!is_string($key)) {
            throw new InvalidArgumentException('Key most be string');
        }
        /*if(!preg_match(RegexpPatternEnum::LOGIN_REQUIRED, $key)) {
            throw new InvalidArgumentException('Invalid key format');
        }*/
    }

}
