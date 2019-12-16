<?php

namespace yubundle\reference\domain\services;

use domain\news\v1\behaviors\CategoryReferenceBookBehavior;
use yubundle\common\behaviors\query\StatusBehavior;
use yii2rails\extension\common\enums\StatusEnum;
use yii2rails\domain\behaviors\query\QueryFilter;
use yubundle\reference\domain\behaviors\ReferenceBookBehavior;
use yubundle\reference\domain\interfaces\services\EnumInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class EnumService
 * 
 * @package yubundle\reference\domain\services
 * 
 * @property-read \yubundle\reference\domain\Domain $domain
 * @property-read \yubundle\reference\domain\interfaces\repositories\EnumInterface $repository
 */
class EnumService extends BaseActiveService implements EnumInterface {

    public $referenceBookId;

    public function getRepository($name = null)
    {
        return \App::$domain->reference->repositories->enum;
    }
    public function behaviors()
    {
        return [
            [
                'class' => ReferenceBookBehavior::class,
                'referenceBookId' => $this->referenceBookId,
            ],
            [
                'class' => StatusBehavior::class,
                'value' => StatusEnum::ENABLE,
            ],
            [
                'class' => QueryFilter::class,
                'method' => 'orderBy',
                'params' => ['sort' => SORT_ASC],
            ],
            [
                'class' => QueryFilter::class,
                'method' => 'with',
                'params' => 'localizations',
            ],
        ];
    }

    public function create($data)
    {
        $data['book_id'] = $this->referenceBookId;
        return parent::create($data); // TODO: Change the autogenerated stub
    }
}