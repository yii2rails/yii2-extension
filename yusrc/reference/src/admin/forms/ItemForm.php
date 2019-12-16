<?php

namespace yubundle\reference\admin\forms;

use Yii;
use yii2rails\domain\base\Model;

class ItemForm extends Model {

    public $reference_book_id;
    public $parent_id;
	public $code;
	public $value;
	public $short_value;
	public $entity;

	public function attributeLabels()
	{
		return [
		    'code' => Yii::t('reference/main', 'code'),
            'value' => Yii::t('reference/main', 'value'),
            'short_value' => Yii::t('reference/main', 'short_value'),
            'entity' => Yii::t('reference/main', 'entity'),
		];
	}
}
