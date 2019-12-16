<?php

namespace yubundle\user\domain\v1\entities;

use Yii;
use yii2rails\app\domain\helpers\EnvService;
use yubundle\staff\domain\v1\entities\WorkerEntity;
use yii2rails\extension\validator\IinValidator;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2bundle\geo\domain\helpers\PhoneHelper;
use yii2bundle\geo\domain\validators\PhoneValidator;
use yii2rails\domain\BaseEntityWithProperties;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\reference\domain\entities\ItemEntity;
use yubundle\user\domain\v1\validators\UserBirthdayValidator;
use yubundle\account\domain\v2\validators\UserNameValidator;
use yii\helpers\ArrayHelper;

/**
 * Class PersonEntity
 * 
 * @package yubundle\user\domain\v1\entities
 * 
 * @property $id
 * @property $first_name
 * @property $last_name
 * @property $middle_name
 * @property $email
 * @property $phone
 * @property $birthday
 * @property $code
 * @property $sex_id
 * @property-read $full_name
 * @property WorkerEntity $worker
 * @property LoginEntity $user
 * @property $avatar
 * @property ItemEntity $sex
 */
class PersonEntity extends BaseEntityWithProperties {

	protected $id;
    protected $first_name;
    protected $last_name;
    protected $middle_name;
    protected $email;
    protected $phone;
    protected $birthday;
    protected $code;
    protected $sex_id;
    protected $worker;
    protected $user;
    protected $status = 1;
    protected $avatar;
    protected $data;
    protected $sex;

    public function getFullName() {
        $fullName = $this->first_name . SPC . $this->last_name;
        return trim($fullName);
    }

    public function getInitial() {
        $initial = mb_substr($this->first_name, 0, 1) . mb_substr($this->last_name, 0, 1);
        return $initial;
    }

    public function getEmail() {
        if(\App::$domain->has('mail')) {
            if ($this->email == null) {
                $login = ArrayHelper::getValue($this, 'user.login');
                if($login) {
                    return $login . '@yuwert.kz';
                }
            } else {
                try {
                    $boxEntity = \App::$domain->mail->box->oneByPersonId($this->id);
                    return $boxEntity->email;
                } catch (NotFoundHttpException $e) {}
            }
        } else {
            return $this->email;
        }
    }

    public function setPhone($phone) {
    	$this->phone = PhoneHelper::clean($phone);
    }

    public function getAvatarUrl() {
        if (!empty($this->avatar)) {
            return EnvService::getStaticUrl($this->avatar);
        }
        return $this->avatar;
    }

	public function rules() {
		return [
			[['first_name', 'last_name', 'middle_name', 'email', 'phone', 'birthday'], 'trim'],
			[['first_name', 'last_name', /*'middle_name', 'email',*/ 'phone', 'birthday'], 'required'],
			[['first_name', 'last_name', 'middle_name'], 'string', 'min' => 2],
            [['first_name', 'last_name', 'middle_name'], UserNameValidator::class],
            [['birthday'], 'date', 'format' => 'php:Y-m-d','message' => 'Формат даты должен быть гггг-мм-дд'],
            ['birthday', UserBirthdayValidator::class],
			['phone', PhoneValidator::class],
            ['code', IinValidator::class],
		];
	}

    public function fieldType()
    {
        return [
            'worker' => WorkerEntity::class,
            'user' => LoginEntity::class,
            'sex' => ItemEntity::class
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['full_name'] = 'full_name';
        $fields['initial'] = 'initial';
        $fields['avatar_url'] = 'avatar_url';
        return $fields;
    }

    public function attributeLabels()
    {
        return [
            'code' => Yii::t('user/person', 'code'),
        ];
    }
}
