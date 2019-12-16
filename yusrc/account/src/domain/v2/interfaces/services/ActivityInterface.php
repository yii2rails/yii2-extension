<?php

namespace yubundle\account\domain\v2\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface ActivityInterface
 * 
 * @package yubundle\account\domain\v2\interfaces\services
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 * @property-read \yubundle\account\domain\v2\interfaces\repositories\ActivityInterface $repository
 */
interface ActivityInterface extends CrudInterface {

}
