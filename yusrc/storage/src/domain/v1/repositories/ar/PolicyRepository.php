<?php

namespace yubundle\storage\domain\v1\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\storage\domain\v1\interfaces\repositories\PolicyInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class PolicyRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class PolicyRepository extends BaseActiveArRepository implements PolicyInterface {

	protected $schemaClass = true;

}
