<?php

namespace yubundle\reference\domain\services;

use yubundle\reference\domain\interfaces\services\ItemInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ItemService
 * 
 * @package yubundle\reference\domain\services
 * 
 * @property-read \yubundle\reference\domain\Domain $domain
 * @property-read \yubundle\reference\domain\interfaces\repositories\ItemInterface $repository
 */
class ItemService extends BaseActiveService implements ItemInterface {

}
