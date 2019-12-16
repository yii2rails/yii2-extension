<?php

namespace yubundle\reference\domain\repositories\ar;

use yubundle\reference\domain\interfaces\repositories\EnumInterface;
use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class EnumRepository
 * 
 * @package yubundle\reference\domain\repositories\ar
 * 
 * @property-read \yubundle\reference\domain\Domain $domain
 */
class EnumRepository extends BaseActiveArRepository implements EnumInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'reference_item';
    }

    public function fieldAlias()
    {
        return [
            'title' => 'value',
            'name' => 'entity',
            'book_id' => 'reference_book_id',
        ];
    }

}
