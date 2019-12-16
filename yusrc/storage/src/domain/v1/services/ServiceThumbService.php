<?php

namespace yubundle\storage\domain\v1\services;

use yubundle\storage\domain\v1\interfaces\services\ServiceThumbInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ServiceThumbService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\ServiceThumbInterface $repository
 */
class ServiceThumbService extends BaseActiveService implements ServiceThumbInterface {

}
