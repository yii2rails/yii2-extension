<?php

namespace yubundle\reference\admin\forms;

use Yii;
use yii2rails\domain\base\Model;

class BookForm extends Model {
	
	public $name;
	public $levels;
	public $entity;
	public $owner_id;

	public function attributeLabels()
	{
		return [
			'name' => Yii::t('reference/main', 'name'),
			'levels' => Yii::t('reference/main', 'levels'),
            'entity' => Yii::t('reference/main', 'entity'),
            'owner_id' => Yii::t('reference/main', 'owner_id'),
		];
	}
}
