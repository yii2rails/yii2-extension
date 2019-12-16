<?php

namespace yubundle\storage\domain\v1\validators;

use yii\validators\StringValidator;
use yii2rails\extension\validator\BaseValidator;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\storage\domain\v1\entities\PolicyEntity;

class ExtensionValidator extends BaseValidator {

	public function validateAttribute($model, $attribute) {
        $fileExtension = FileHelper::fileExt($model->$attribute->name);
        $roleCollection = \Yii::$app->user->identity->roles;
        /** @var PolicyEntity $policyEntity */
        $policyEntity = \App::$domain->storage->policy->oneByRoles($roleCollection);
        if (!empty($policyEntity->allow_extensions)) {
            $isInArray = in_array($fileExtension, $policyEntity->allow_extensions);
            if (!$isInArray) {
                $this->addError($model, $attribute, \Yii::t('storage/policy', 'file_type'));
            }
        }
	}
}
