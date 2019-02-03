<?php

namespace yii2rails\extension\common\exceptions;

use yii\base\Exception;
use yii\web\HttpException;

class CreatedHttpExceptionException extends HttpException {
	
	public function __construct($message = null, $code = 0, \Exception $previous = null) {
		parent::__construct(202, $message, $code, $previous);
	}
}
