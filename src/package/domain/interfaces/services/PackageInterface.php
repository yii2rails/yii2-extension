<?php

namespace yii2rails\extension\package\domain\interfaces\services;

use yii2rails\domain\interfaces\services\ReadInterface;

/**
 * Interface PackageInterface
 * 
 * @package yii2rails\extension\package\domain\interfaces\services
 * 
 * @property-read \yii2rails\extension\package\domain\Domain $domain
 * @property-read \yii2rails\extension\package\domain\interfaces\repositories\PackageInterface $repository
 */
interface PackageInterface extends ReadInterface {

}
