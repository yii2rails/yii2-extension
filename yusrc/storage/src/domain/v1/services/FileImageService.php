<?php

namespace yubundle\storage\domain\v1\services;

use yubundle\storage\domain\v1\interfaces\services\FileImageInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class FileImageService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileImageInterface $repository
 */
class FileImageService extends BaseActiveService implements FileImageInterface {

}
