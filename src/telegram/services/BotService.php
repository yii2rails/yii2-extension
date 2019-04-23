<?php

namespace yii2rails\extension\telegram\services;

use yii2rails\extension\telegram\interfaces\services\BotInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class BotService
 * 
 * @package yii2rails\extension\telegram\services
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 * @property-read \yii2rails\extension\telegram\interfaces\repositories\BotInterface $repository
 */
class BotService extends BaseActiveService implements BotInterface {

}
