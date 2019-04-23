<?php

namespace yii2rails\extension\telegram\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface UserInterface
 * 
 * @package yii2rails\extension\telegram\interfaces\services
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 * @property-read \yii2rails\extension\telegram\interfaces\repositories\UserInterface $repository
 */
interface UserInterface extends CrudInterface {

    public function updateState($userId, $botId, $state);
    public function oneByFrom($from, $botId);

}
