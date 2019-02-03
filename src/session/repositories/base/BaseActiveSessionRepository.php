<?php

namespace yii2rails\extension\session\repositories\base;

use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2rails\extension\arrayTools\traits\ArrayModifyTrait;
use yii2rails\extension\arrayTools\traits\ArrayReadTrait;

abstract class BaseActiveSessionRepository extends BaseSessionRepository implements CrudInterface {
	
	use ArrayReadTrait;
	use ArrayModifyTrait;
	
}