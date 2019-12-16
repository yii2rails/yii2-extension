<?php

namespace yubundle\account\domain\v2\forms\registration;

use yii\base\Model;
use yii2bundle\geo\domain\validators\PhoneValidator;
use yii2bundle\account\domain\v3\helpers\LoginTypeHelper;
use yubundle\account\domain\v2\validators\PasswordValidator;
use yubundle\account\domain\v2\validators\UserBirthdayValidator;
use yubundle\account\domain\v2\validators\UserLoginValidator;
use yii2rails\domain\helpers\Helper;
use yubundle\account\domain\v2\validators\UserNameValidator;

class PersonInfoForm extends Model {
	
	const SCENARIO_PERSON_INFO = 'SCENARIO_PERSON_INFO';
	const SCENARIO_VERIFY_CODE = 'SCENARIO_VERIFY_CODE';
	const SCENARIO_REQUEST_CODE = 'SCENARIO_REQUEST_CODE';
	const SCENARIO_CREATE_ACCOUNT = 'SCENARIO_CREATE_ACCOUNT';
	const SCENARIO_CREATE_WORKER = 'SCENARIO_CREATE_WORKER';

	public $first_name;
	public $last_name;
	public $middle_name;
	public $login;
	public $birthday_day;
	public $birthday_month;
	public $birthday_year;
	public $phone;
	public $password;
	public $activation_code;
	public $birth_date;
	public $company_id;

	public function rules() {
		return [
            [[
                'first_name', 'last_name', 'login',
                'birthday_day', 'birthday_month', 'birthday_year',
                'phone', 'password'], 'trim'],
            [[
                'first_name', 'last_name', 'login',
                'birthday_day', 'birthday_month', 'birthday_year',
                'phone', 'password','birth_date'], 'required'],

            [['birth_date'], 'date', 'format' => 'php:Y-m-d','message' => 'Формат даты должен быть гггг-мм-дд'],

            ['phone', 'normalizeLogin'],

            [['first_name', 'last_name'], 'string', 'min' => 2],

            ['birthday_day', 'integer', 'min' => 1, 'max' => 31],
            ['birthday_month', 'integer', 'min' => 1, 'max' => 12],
            ['birthday_year', 'integer', 'min' => 1800, 'max' => intval(date('Y'))],
            ['birthday_year', UserBirthdayValidator::class],
            [['first_name', 'last_name', 'middle_name'], UserNameValidator::class],
			['login', UserLoginValidator::class],
			['phone', PhoneValidator::class],
			['password', PasswordValidator::class],

			[['activation_code'], 'integer'],
			[['activation_code'], 'string', 'length' => 6],
		];
	}
	
	public function scenarios() {
		return [
			self::SCENARIO_PERSON_INFO => ['first_name', 'last_name', 'middle_name', 'login',
				'birthday_day', 'birthday_month', 'birthday_year',
				'phone', 'password'],
			self::SCENARIO_VERIFY_CODE => ['phone', 'activation_code'],
			self::SCENARIO_REQUEST_CODE => ['phone'],
			self::SCENARIO_CREATE_ACCOUNT => ['first_name', 'last_name', 'middle_name', 'login',
				'birthday_day', 'birthday_month', 'birthday_year',
				'phone', 'password',
				'activation_code'
			],
            self::SCENARIO_CREATE_WORKER => ['first_name', 'last_name', 'middle_name','birth_date'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
        $labels = Helper::forgeLabels([
            'first_name', 'last_name', 'login',
            'birthday_day', 'birthday_month', 'birthday_year',
            'phone', 'code',
        ], 'user/person');
		$labels = Helper::forgeLabels(['activation_code'], 'user/registration', $labels);
        $labels = Helper::forgeLabels(['password'], 'user/account', $labels);
		return $labels;
	}

    public function normalizeLogin($attribute)
    {
        $value = $this->$attribute;
        $value = LoginTypeHelper::normalize($value);
        $this->$attribute = $value;
    }

}
