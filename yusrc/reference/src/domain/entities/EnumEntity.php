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
 * @property $book_id
 * @property $created_at
 * @property $updated_at
 * @property LocalizationEntity $localization
 * @property LocalizationEntity[] $localizations
 */
class EnumEntity extends BaseEntity {

	protected $id;
	protected $title;
	protected $name;
    protected $sort;
    protected $localization;
    protected $localizations;
    protected $book_id;
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

    /**
     * @return LocalizationEntity
     */
    public function getLocalization() {
        if(empty($this->localization)) {
            $localizations = $this->getLocalizations();
            if(empty($localizations)) {
                return null;
            }
            $localizations = ArrayHelper::toArray($localizations, [], false);
            $localizations = ArrayHelper::index($localizations, 'language_code');
            $lang = \Yii::$app->language;
            if(!isset($localizations[$lang])) {
                $lang = ArrayHelper::firstKey($localizations);
            }
            $this->localization = $localizations[$lang];
        }
        return $this->localization;
    }

    public function setLocalization($value) {

    }

    public function getLocalizations() {
        if($this->localizations) {
            return $this->localizations;
        }
        $loc = new LocalizationEntity;
        $loc->value = $this->title;
        $loc->language_code = \Yii::$app->language;
        $collection = [
            \Yii::$app->language => $loc,
        ];
        return $collection;
    }
}
