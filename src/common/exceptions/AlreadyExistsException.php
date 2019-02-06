<?php

namespace yii2rails\extension\common\exceptions;

use yii\base\Exception;

class AlreadyExistsException extends Exception {
	
	public function getName() {
		return 'AlreadyExistsException';
	}
}
