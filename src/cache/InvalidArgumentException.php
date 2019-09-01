<?php

namespace yii2rails\extension\cache;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException as InvalidArgumentExceptionInterface;

class InvalidArgumentException extends \InvalidArgumentException implements InvalidArgumentExceptionInterface {

}
