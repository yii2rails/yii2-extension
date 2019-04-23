<?php

namespace yii2rails\extension\telegram\services;

use yii2rails\extension\telegram\interfaces\services\ActionInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ActionService
 * 
 * @package yii2rails\extension\telegram\services
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 * @property-read \yii2rails\extension\telegram\interfaces\repositories\ActionInterface $repository
 */
class ActionService extends BaseActiveService implements ActionInterface {

}
