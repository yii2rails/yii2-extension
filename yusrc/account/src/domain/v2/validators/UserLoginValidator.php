<?php

namespace yubundle\account\domain\v2\validators;

use Yii;
use yii\validators\RegularExpressionValidator;
use yii2rails\extension\common\enums\RegexpPatternEnum;
use yii2rails\extension\validator\BaseValidator;

class UserLoginValidator extends BaseValidator {

	public function validateAttribute($model, $attribute) {
        $model->$attribute = trim($model->$attribute);
        $model->$attribute = mb_strtolower($model->$attribute);
        $validator = Yii::createObject([
            'class' => RegularExpressionValidator::class,
            'pattern' => '#^[a-z0-9._-]{3,16}$#',
        ]);
        $validator->validateAttribute($model, $attribute);
        /*$validator = Yii::createObject([
            'class' => RegularExpressionValidator::class,
            'pattern' => '/^[a-zA-Z]$/',
        ]);
        $validator->validateAttribute($model, $attribute);*/
	}

}
