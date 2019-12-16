<?php

namespace yubundle\user\domain\v1\validators;

use Yii;
use yii\validators\StringValidator;
use yii2rails\extension\validator\BaseValidator;

class UserBirthdayValidator extends BaseValidator {

	public function validateAttribute($model, $attribute) {
        $model->$attribute = trim($model->$attribute);
        $todayDate = date('Y-m-d');
        if($todayDate < $model->$attribute){
            $this->addError($model, $attribute, Yii::t('account/main', 'bad_date'));
        }
	}
}
