<?php

namespace yubundle\account\domain\v2\models;

use yii\db\ActiveRecord;
use yii2lab\db\domain\behaviors\json\JsonBehavior;
use yii2lab\db\domain\helpers\TableHelper;

/**
 * Class UserConfirm
 *
 * @package yubundle\account\domain\v2\models
 *
 * @property $login
 * @property $action
 * @property $code
 * @property $data
 * @property $expire
 * @property $created_at
 */
class UserConfirm extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
        return TableHelper::getGlobalName('user_confirm');
	}
	
	public static function primaryKey()
	{
		return ['login', 'action'];
	}
	
	public function behaviors()
	{
		return [
			'rulesJson' => [
				'class' => JsonBehavior::class,
				'attributes' => ['data'],
			],
		];
	}
	
}
