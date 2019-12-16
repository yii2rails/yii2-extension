<?php

namespace yubundle\reference\domain\services;

use yii\db\Expression;
use yii2rails\domain\behaviors\query\QueryFilter;
use yii2rails\domain\data\Query;
use yubundle\reference\domain\entities\BookEntity;
use yubundle\reference\domain\interfaces\services\BookInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ItemService
 *
 * @package yubundle\reference\domain\services
 *
 * @property-read \yubundle\reference\domain\Domain $domain
 * @property-read \yubundle\reference\domain\interfaces\repositories\BookInterface $repository
 */
class BookService extends BaseActiveService implements BookInterface
{

    protected function prepareQuery(Query $query = null)
    {
        $companyId = \App::$domain->account->auth->identity->company_id;
        $query = Query::forge($query);
        $query->andWhere(new Expression('(owner_id = ' . $companyId . ' or owner_id is null)'));
        return $query;
    }

    public function oneByEntity(string $entity, Query $query = null) : BookEntity {
        $query = Query::forge($query);
        $query->andWhere(['entity' => $entity]);
        return $this->one($query);
    }

}
