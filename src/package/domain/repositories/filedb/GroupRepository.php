<?php

namespace yii2lab\extension\package\domain\repositories\filedb;

use yii2lab\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yii2lab\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2lab\extension\package\domain\interfaces\repositories\GroupInterface;

/**
 * Class GroupRepository
 * 
 * @package yii2lab\extension\package\domain\repositories\filedb
 * 
 * @property-read \yii2lab\extension\package\domain\Domain $domain
 */
class GroupRepository extends BaseActiveDiscRepository implements GroupInterface {

	protected $schemaClass = false;
	public $path = '@common/data';
	public $table = 'package_group';
}
