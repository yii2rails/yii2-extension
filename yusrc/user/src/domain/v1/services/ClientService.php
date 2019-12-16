<?php

namespace yubundle\user\domain\v1\services;

use yii2bundle\geo\domain\helpers\PhoneHelper;
use yubundle\common\behaviors\query\StatusBehavior;
use yii2rails\extension\common\enums\StatusEnum;
use yubundle\user\domain\v1\entities\ClientEntity;
use yubundle\user\domain\v1\interfaces\services\ClientInterface;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ClientService
 * 
 * @package yubundle\user\domain\v1\services
 * 
 * @property-read \yubundle\user\domain\v1\Domain $domain
 * @property-read \yubundle\user\domain\v1\interfaces\repositories\ClientInterface $repository
 */
class ClientService extends BaseActiveService implements ClientInterface {

    public function oneByPersonId($personId, Query $query = null) : ClientEntity
    {
        $query = Query::forge($query);
        $query->andWhere(['person_id' => $personId]);
        return $this->repository->one($query);
    }
	
}
