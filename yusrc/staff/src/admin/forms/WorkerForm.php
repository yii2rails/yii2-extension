<?php

namespace yubundle\staff\admin\forms;

use Yii;
use yii2bundle\geo\domain\validators\PhoneValidator;
use yii2bundle\account\domain\v3\helpers\LoginTypeHelper;
use yii2rails\domain\base\Model;
use yii2rails\extension\validator\IinValidator;

class WorkerForm extends Model {

    const SCENARIO_CREATE_WORKER = 'SCENARIO_CREATE_WORKER';
    const SCENARIO_UPDATE_WORKER = 'SCENARIO_UPDATE_WORKER';

    // для сущности PersonEntity
    public $first_name;
    public $last_name;
    public $middle_name;
    public $birth_date;
    public $code;
    public $sex;
    public $outer_email; // это личная почта
    public $outer_phone; // это личный телефон

    // для сущности WorkerEntity
    public $division;
    public $post;
    public $office;
    public $private_phone; // это корпоративный телефон

    // для сущности LoginEntity
    public $user_login;
    public $user_password;
    public $accept_password;
    public $entity;

    //для валидации ДР
    public $birthday_day;
    public $birthday_month;
    public $birthday_year;

    public function rules() {
        return [
            [[
                'first_name', 'last_name','birth_date','code','sex','outer_phone', 'office', 'user_login', 'user_password', 'accept_password',
            ], 'required'],
            [['birth_date'], 'date', 'format' => 'php:Y-m-d','message' => 'Формат даты должен быть гггг-мм-дд'],
            ['code',IinValidator::class],
            ['outer_phone', PhoneValidator::class],
            ['private_phone','number'],
            [['office'], 'match', 'pattern' => '/^[\w0-9\s.]+$/u','message'=>'Разрешены только буквы и цифры'],
        ];
    }

    public function scenarios() {
        return [
            self::SCENARIO_CREATE_WORKER => [
                'first_name', 'last_name', 'middle_name','birth_date','code','sex',
                'outer_phone','office','private_phone', 'user_login', 'user_password', 'accept_password'
            ],
            self::SCENARIO_UPDATE_WORKER => [
                'first_name', 'last_name', 'middle_name','birth_date','code','sex',
                'outer_phone','office','private_phone',
            ],
        ];
    }

	public function attributeLabels()
	{
		return [
            'first_name' => Yii::t('staff/worker', 'first_name'),
            'last_name' => Yii::t('staff/worker', 'last_name'),
            'middle_name' => Yii::t('staff/worker', 'middle_name'),
            'private_phone' => Yii::t('staff/worker', 'phone'),
            'code' => Yii::t('staff/worker', 'code'),
            'user_login' => Yii::t('staff/worker', 'login'),
            'user_password' => Yii::t('staff/worker', 'password'),
            'accept_password' => Yii::t('staff/worker', 'accept_password'),
            'sex' => Yii::t('staff/worker', 'sex'),
            'post' => Yii::t('staff/worker', 'post'),
            'division' => Yii::t('staff/worker', 'division'),
            'birth_date' => Yii::t('staff/worker', 'birthday'),
            'outer_email' => Yii::t('staff/worker', 'outer_email'),
            'outer_phone' => Yii::t('staff/worker', 'outer_phone'),
            'office' => Yii::t('staff/worker', 'office'),
		];
	}
}
