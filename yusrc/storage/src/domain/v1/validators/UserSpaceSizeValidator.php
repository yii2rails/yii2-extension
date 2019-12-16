<?php

namespace yubundle\storage\domain\v1\validators;

use yii\validators\StringValidator;
use yii2rails\extension\validator\BaseValidator;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\storage\domain\v1\entities\PolicyEntity;
use yii2rails\domain\data\Query;

class UserSpaceSizeValidator extends BaseValidator {

	public function validateAttribute($model, $attribute) {
        $fileSize = $model->$attribute->size;
        $roleCollection = \Yii::$app->user->identity->roles;
        /** @var PolicyEntity $policyEntity */
        $policyEntity = \App::$domain->storage->policy->oneByRoles($roleCollection);
        $personId = \Yii::$app->user->identity->person_id;
        $query = new Query();
        $query->andWhere(['editor_id' => $personId]);
        $isServiceIdExist = property_exists($model, 'servoce_id');
        if ($isServiceIdExist) {
            $query->andWhere(['service_id' => $model]);
        }
        $fileEntity = \App::$domain->storage->file->filesSize($query);
        $fileCollectionSize = $fileEntity->size + $fileSize;
        if (!empty($policyEntity->space_size)) {
            if ($fileCollectionSize > $policyEntity->space_size) {
                $this->addError($model, $attribute, \Yii::t('storage/policy', 'not_enough_space_size'));
            }
        }
	}
}
