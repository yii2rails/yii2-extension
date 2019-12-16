<?php

namespace yubundle\storage\domain\v1\validators;

use Yii;
use yii\validators\StringValidator;
use yii2rails\extension\validator\BaseValidator;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\storage\domain\v1\entities\PolicyEntity;

class TypeValidator extends BaseValidator {

	public function validateAttribute($model, $attribute) {
        $fileType = $model->$attribute->type;
        $roleCollection = \Yii::$app->user->identity->roles;
        /** @var PolicyEntity $policyEntity */
        $policyEntity = \App::$domain->storage->policy->oneByRoles($roleCollection);
        if (!empty($policyEntity->allow_types)) {
            $isInArray = in_array($fileType, $policyEntity->allow_types);
            if (!$isInArray) {
                $this->addError($model, $attribute, \Yii::t('storage/policy', 'file_type'));
            }
        }
	}
}
