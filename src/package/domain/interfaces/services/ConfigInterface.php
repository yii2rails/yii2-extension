<?php

namespace yii2rails\extension\package\domain\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;
use yii2rails\extension\package\domain\entities\ConfigEntity;

/**
 * Interface ConfigInterface
 * 
 * @package yii2rails\extension\package\domain\interfaces\services
 * 
 * @property-read \yii2rails\extension\package\domain\Domain $domain
 * @property-read \yii2rails\extension\package\domain\interfaces\repositories\ConfigInterface $repository
 */
interface ConfigInterface extends CrudInterface {

    public function oneByDir(string $dir) : ConfigEntity;

}
