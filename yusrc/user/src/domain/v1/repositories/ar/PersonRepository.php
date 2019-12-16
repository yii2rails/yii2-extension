<?php

namespace yubundle\user\domain\v1\repositories\ar;

use yii2rails\domain\behaviors\query\SearchFilter;
use yubundle\user\domain\v1\interfaces\repositories\PersonInterface;
use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class PersonRepository
 * 
 * @package yubundle\user\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\user\domain\v1\Domain $domain
 */
class PersonRepository extends BaseActiveArRepository implements PersonInterface {

	protected $schemaClass = true;

	function tableName()
    {
        return 'user_person';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SearchFilter::class,
                'fields' => [
                    'name',
                    'first_name',
                    'last_name',
                    'middle_name',
                    'phone',
                ],
                'virtualFields' => [
                    'name' => [
                        'first_name',
                        'last_name',
                        'middle_name',
                        'code',
                        'phone',
                    ],
                ],
            ],
        ];
    }
}
