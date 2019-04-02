<?php

namespace yii2rails\extension\changelog\services;

use yii2rails\extension\changelog\interfaces\services\WordInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class WordService
 * 
 * @package yii2rails\extension\changelog\services
 * 
 * @property-read \yii2rails\extension\changelog\Domain $domain
 * @property-read \yii2rails\extension\changelog\interfaces\repositories\WordInterface $repository
 */
class WordService extends BaseActiveService implements WordInterface {

}
