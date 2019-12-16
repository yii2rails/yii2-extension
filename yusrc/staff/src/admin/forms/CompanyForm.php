<?php

namespace yubundle\staff\admin\forms;

use Yii;
use yii2rails\domain\base\Model;
use yii2rails\extension\common\enums\StatusEnum;

class CompanyForm extends Model {

	public $code;
	public $name;
	public $status = StatusEnum::ENABLE;

	public function attributeLabels()
	{
		return [
			'code' => Yii::t('staff/company', 'code'),
			'name' => Yii::t('staff/company', 'name'),
            'status' => Yii::t('staff/company', 'status'),
		];
	}
}
