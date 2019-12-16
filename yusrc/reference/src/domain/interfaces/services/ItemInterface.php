<?php

namespace yubundle\reference\domain\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface ItemInterface
 * 
 * @package yubundle\reference\domain\interfaces\services
 * 
 * @property-read \yubundle\reference\domain\Domain $domain
 * @property-read \yubundle\reference\domain\interfaces\repositories\ItemInterface $repository
 */
interface ItemInterface extends CrudInterface {

}
