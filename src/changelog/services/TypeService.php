<?php

namespace yii2rails\extension\changelog\services;

use yii2rails\extension\changelog\interfaces\services\TypeInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class TypeService
 * 
 * @package yii2rails\extension\changelog\services
 * 
 * @property-read \yii2rails\extension\changelog\Domain $domain
 * @property-read \yii2rails\extension\changelog\interfaces\repositories\TypeInterface $repository
 */
class TypeService extends BaseActiveService implements TypeInterface {

}
