<?php

namespace yii2rails\extension\package\domain\services;

use yii2rails\domain\services\base\BaseActiveService;
use yii2rails\extension\package\domain\interfaces\services\PackageInterface;

/**
 * Class PackageService
 * 
 * @package yii2rails\extension\package\domain\services
 * 
 * @property-read \yii2rails\extension\package\domain\Domain $domain
 * @property-read \yii2rails\extension\package\domain\interfaces\repositories\PackageInterface $repository
 */
class PackageService extends BaseActiveService implements PackageInterface {

}
