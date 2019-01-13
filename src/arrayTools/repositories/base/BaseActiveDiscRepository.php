<?php

namespace yii2lab\extension\arrayTools\repositories\base;

use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii2lab\extension\arrayTools\traits\ArrayModifyTrait;
use yii2lab\extension\arrayTools\traits\ArrayReadTrait;

abstract class BaseActiveDiscRepository extends BaseDiscRepository implements CrudInterface {

	use ArrayReadTrait;
	use ArrayModifyTrait;
	
}