<?php

namespace yubundle\reference\domain\entities;

use yii2rails\extension\yii\helpers\ArrayHelper;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;

/**
 * Class EnumEntity
 * 
 * @package yubundle\reference\domain\entities
 * 
 * @property $id
 * @property $title
 * @property $name
 * @property $sort
 * @property $created_at
 * @property $updated_at
 * @property LocalizationEntity $localization
 * @property LocalizationEntity[] $localizations
 */
class BookEntity extends BaseEntity {

	protected $id;
	protected $name;
	protected $levels;
    protected $entity;
    protected $owner_id = 1;
    protected $props;
    protected $sort;
    protected $localization;
    protected $localizations;
    protected $created_at;
    protected $updated_at;

    public function fieldType()
    {
        return [
            'id' => 'integer',
            'localization' => LocalizationEntity::class,
            'localizations' => [
                'type' => LocalizationEntity::class,
                'isCollection' => true,
            ],
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
        ];
    }

    public function init()
    {
        parent::init();
        $this->created_at = new TimeValue;
        $this->created_at->setNow();
        $this->updated_at = new TimeValue;
        $this->updated_at->setNow();
    }

    public function fields() {
        $fields = parent::fields();
        unset($fields['localization']);
        unset($fields['localizations']);
        return $fields;
    }

    public function getTitle() {
        $localization = $this->getLocalization();
        if($localization instanceof LocalizationEntity) {
            return $localization->value;
        }
        return $this->title;
    }
}
