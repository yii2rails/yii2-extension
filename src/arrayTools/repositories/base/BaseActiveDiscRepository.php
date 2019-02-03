<?php

namespace yii2rails\extension\arrayTools\repositories\base;

use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2rails\extension\arrayTools\traits\ArrayModifyTrait;
use yii2rails\extension\arrayTools\traits\ArrayReadTrait;

abstract class BaseActiveDiscRepository extends BaseDiscRepository implements CrudInterface {

	use ArrayReadTrait;
	use ArrayModifyTrait;
	
}