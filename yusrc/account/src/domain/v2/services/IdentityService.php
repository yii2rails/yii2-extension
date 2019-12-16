<?php

namespace yubundle\account\domain\v2\services;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\interfaces\services\IdentityInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class IdentityService
 * 
 * @package yubundle\account\domain\v2\services
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 * @property-read \yubundle\account\domain\v2\interfaces\repositories\IdentityInterface $repository
 */
class IdentityService extends BaseActiveService implements IdentityInterface {

    protected function prepareQuery(Query $query = null)
    {
        $query = Query::forge($query);
        $phone = $query->getWhere('phone');
        if($phone) {
            $query->removeWhere('phone');
            $personIds = [];
            try {
                $personEntity = \App::$domain->user->person->oneByPhone($phone);
                $personIds[] = $personEntity->id;
                $query->andWhere(['person_id' => $personIds]);
            } catch (NotFoundHttpException $e) {
                $query->andWhere(['person_id' => 0]);
            }
        }
        return $query;
    }

}
