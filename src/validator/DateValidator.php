<?php

namespace yii2rails\extension\validator;

use Yii;
use yii\validators\Validator;
use yii2rails\extension\common\helpers\Helper;

class DateValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{
		$isValid = false;
		$exp = '
			(\d{4})-(\d\d)-(\d\d)
			T
			(\d\d):(\d\d):(\d\d)
			(\.\d+)?
			(([+-]\d\d:\d\d)|Z|O)?
		';
		if (preg_match('/^' . $exp . '$/ix', $model->$attribute, $parts) == true) {
			$time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);
			$input_time = strtotime($model->$attribute);
			if ($input_time === false) return false;
			$isValid = $input_time == $time;
		}
		if(!$isValid) {
			//$this->addError($attribute, Yii::t('yii', 'The format of {attribute} is invalid.', ['attribute' => $attribute]));
			$this->addError($attribute, Yii::t('yii', 'The format of {attribute} is invalid.', ['attribute' => 'Birth Date']));
		} else {
			$this->$attribute = date('Y-m-d H:i:s', $time);
		}
	}
}
