<?php

namespace yii2lab\extension\validator;

use Yii;
use yii\validators\Validator;
use yii2lab\extension\common\helpers\Helper;

class  ClientTimeValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{
		if (!preg_match('#^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$#', $model->$attribute)) {
			$this->addError($model, $attribute, Yii::t('validaor','invalid_client_time_format'));
		}
	}
}
