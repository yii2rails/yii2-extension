<?php

namespace yubundle\staff\domain\v1\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface PostInterface
 * 
 * @package yubundle\staff\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 * @property-read \yubundle\staff\domain\v1\interfaces\repositories\PostInterface $repository
 */
interface PostInterface extends CrudInterface {

}
