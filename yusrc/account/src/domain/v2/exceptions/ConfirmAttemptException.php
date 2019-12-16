<?php

namespace yubundle\account\domain\v2\exceptions;

use yii\base\Exception;

class ConfirmAttemptException extends Exception
{
	public function getName()
	{
		return 'Number of attempts exhausted';
	}
}