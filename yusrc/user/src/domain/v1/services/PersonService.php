<?php

namespace yubundle\user\domain\v1\services;

use yii2bundle\geo\domain\helpers\PhoneHelper;
use yubundle\common\behaviors\query\StatusBehavior;
use yii2rails\extension\common\enums\StatusEnum;
use yubundle\user\domain\v1\entities\PersonEntity;
use yubundle\user\domain\v1\interfaces\services\PersonInterface;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class PersonService
 * 
 * @package yubundle\user\domain\v1\services
 * 
 * @property-read \yubundle\user\domain\v1\Domain $domain
 * @property-read \yubundle\user\domain\v1\interfaces\repositories\PersonInterface $repository
 */
class PersonService extends BaseActiveService implements PersonInterface {

    public function behaviors()
    {
        return [
            [
                'class' => StatusBehavior::class,
                'value' => StatusEnum::ENABLE,
            ],
        ];
    }
	
	public function isExistsByPhone($phone) {
		try {
			$this->oneByPhone($phone);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
    
    public function oneByPhone($phone, Query $query = null) {
	    $query = $this->prepareQuery($query);
	    $phone = PhoneHelper::clean($phone);
	    $query->andWhere(['phone' => $phone]);
	    return parent::one($query);
    }

    public function oneSelf(Query $query = null) {
        $personId = \App::$domain->account->auth->identity->person_id;
        return parent::oneById($personId, $query);
    }

    public function updateSelf(PersonEntity $personEntity) {
        $personEntitySource = $this->oneById(\App::$domain->account->auth->identity->person_id);
        $personEntity = $personEntity->toArray();
        unset($personEntity['id']);
        $personEntitySource->load($personEntity);
        $personEntitySource->validate();
        $this->repository->update($personEntitySource);
    }

    public function deleteById($id) {
        $data = [
            'status' => StatusEnum::DISABLE,
        ];
        $entity = $this->oneById($id);
        $entity->load($data);
        $this->repository->update($entity);
    }

}
