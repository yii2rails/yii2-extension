<?php

namespace yii2rails\extension\git\domain\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface GitInterface
 * 
 * @package yii2rails\extension\git\domain\interfaces\services
 * 
 * @property-read \yii2rails\extension\git\domain\Domain $domain
 * @property-read \yii2rails\extension\git\domain\interfaces\repositories\GitInterface $repository
 */
interface GitInterface extends CrudInterface {

}
