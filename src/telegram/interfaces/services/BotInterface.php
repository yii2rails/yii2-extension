<?php

namespace yii2rails\extension\telegram\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface BotInterface
 * 
 * @package yii2rails\extension\telegram\interfaces\services
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 * @property-read \yii2rails\extension\telegram\interfaces\repositories\BotInterface $repository
 */
interface BotInterface extends CrudInterface {

}
