<?php

namespace yubundle\account\domain\v2\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\account\domain\v2\interfaces\repositories\ActivityInterface;

/**
 * Class ActivityRepository
 * 
 * @package yubundle\account\domain\v2\repositories\ar
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
class ActivityRepository extends BaseActiveArRepository implements ActivityInterface {
	
	protected $modelClass = 'yubundle\account\domain\v2\models\UserActivity';

}
