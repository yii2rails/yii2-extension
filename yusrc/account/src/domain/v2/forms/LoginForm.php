<?php

namespace yubundle\account\domain\v2\forms;

use Yii;
use yii2bundle\account\domain\v3\helpers\LoginTypeHelper;
use yubundle\account\domain\v2\helpers\LoginHelper;
use yubundle\account\domain\v2\validators\PasswordValidator;
use yii2rails\domain\base\Model;
use yii2bundle\lang\domain\helpers\LangHelper;

class LoginForm extends Model
{
	
	const SCENARIO_SIMPLE = 'SCENARIO_SIMPLE';
	
	public $login;
	public $password;
	//public $email;
	//public $role;
	//public $status;
	public $token_type;
	public $rememberMe = true;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['login', 'password', 'token_type'], 'trim'],
			[['login', 'password'], 'required'],
			//['email', 'email'],
			//['login', 'match', 'pattern' => '/^[0-9_]{11,13}$/i', 'message' => Yii::t('account/registration', 'login_not_valid')],
			//['login', LoginValidator::class],
			'normalizeLogin' => ['login', 'normalizeLogin'],
			//[['password'], PasswordValidator::class],
			['rememberMe', 'boolean'],
		    //[['status'], 'safe'],
		];
	}
	
	public function scenarios() {
		return [
			self::SCENARIO_DEFAULT => [
				'login',
				'password',
				'email',
				'role',
				'status',
				'rememberMe',
				'token_type',
			],
			self::SCENARIO_SIMPLE => [
				'login',
				'password',
				'token_type',
			],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'login' 		=> Yii::t('user/auth', 'login'),
			'password' 		=> Yii::t('account/main', 'password'),
			'rememberMe' 		=> Yii::t('account/auth', 'remember_me'),
		];
	}

	public function normalizeLogin($attribute)
	{
        $value = $this->$attribute;
        $value = LoginTypeHelper::normalize($value);
        $this->$attribute = $value;
	}
	
}
