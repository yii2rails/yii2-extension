<?php

namespace yubundle\storage\domain\v1\validators;

use yii\validators\StringValidator;
use yii2rails\extension\validator\BaseValidator;
use yii2rails\domain\data\Query;
use yubundle\storage\domain\v1\entities\PolicyEntity;

class FileSizeValidator extends BaseValidator {

	public function validateAttribute($model, $attribute) {
        $fileSize = $model->$attribute->size;
        $roleCollection = \Yii::$app->user->identity->roles;
        /** @var PolicyEntity $policyEntity */
        $policyEntity = \App::$domain->storage->policy->oneByRoles($roleCollection);
        if (!empty($policyEntity->file_size)) {
            if ($fileSize > $policyEntity->file_size) {
                $this->addError($model, $attribute, \Yii::t('storage/policy', 'file_size'));
            }
        }
	}

}
