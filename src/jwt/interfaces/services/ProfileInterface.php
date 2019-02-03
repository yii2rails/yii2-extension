<?php

namespace yii2rails\extension\jwt\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface ProfileInterface
 * 
 * @package yii2rails\extension\jwt\interfaces\services
 * 
 * @property-read \yii2rails\extension\jwt\Domain $domain
 * @property-read \yii2rails\extension\jwt\interfaces\repositories\ProfileInterface $repository
 */
interface ProfileInterface extends CrudInterface {

}
