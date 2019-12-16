<?php

namespace yubundle\reference\domain\repositories\ar;

use yii2rails\domain\behaviors\query\SearchFilter;
use yii2rails\domain\data\Query;
use yubundle\reference\domain\interfaces\repositories\ItemInterface;
use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class ItemRepository
 * 
 * @package yubundle\reference\domain\repositories\ar
 * 
 * @property-read \yubundle\reference\domain\Domain $domain
 */
class ItemRepository extends BaseActiveArRepository implements ItemInterface {

	protected $schemaClass = true;
	
	public function tableName()
	{
		return 'reference_item';
	}

    public function behaviors()
    {
        return [
            [
                'class' => SearchFilter::class,
                'fields' => [
                    'value',
                ],
            ],
        ];
    }

    public function checkExistsItemByValueAndBookId($value, $bookId) {
        $query = new Query();
        $query->andWhere(['value' => $value]);
        $entityItem = \App::$domain->reference->item->one($query);
        return $entityItem;
    }
}
