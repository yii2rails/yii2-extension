<?php

namespace yii2rails\extension\telegram\interfaces\services;

use yii2rails\extension\telegram\entities\RouteEntity;
use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface RouteInterface
 * 
 * @package yii2rails\extension\telegram\interfaces\services
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 * @property-read \yii2rails\extension\telegram\interfaces\repositories\RouteInterface $repository
 */
interface RouteInterface extends CrudInterface {

    /**
     * @param $botId
     * @return RouteEntity[]
     */
    public function allByBotId($botId, $state);
}
