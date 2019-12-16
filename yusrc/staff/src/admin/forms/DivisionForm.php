<?php

namespace yubundle\staff\admin\forms;

use Yii;
use yii2rails\domain\base\Model;

class DivisionForm extends Model {

    public $company_id;
    public $parent_id;
    public $name;

	public function attributeLabels()
	{
		return [
            'name' => Yii::t('staff/division', 'name'),
		];
	}
}
