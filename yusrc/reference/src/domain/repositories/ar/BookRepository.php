<?php

namespace yubundle\reference\domain\repositories\ar;

use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\reference\domain\interfaces\repositories\BookInterface;

/**
 * Class ItemRepository
 * 
 * @package yubundle\reference\domain\repositories\ar
 * 
 * @property-read \yubundle\reference\domain\Domain $domain
 */
class BookRepository extends BaseActiveArRepository implements BookInterface {

	protected $schemaClass = true;
	
	public function tableName()
	{
		return 'reference_book';
	}

    public function checkExistsBookByCompanyId($companyId) {
        $query = new Query();
        $query->andWhere(['owner_id' => $companyId]);
        $bookEntity = \App::$domain->reference->book->repository->one($query);
        return $bookEntity;
    }
	
}
