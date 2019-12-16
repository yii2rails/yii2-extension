<?php

namespace yubundle\account\domain\v2\entities;

use paulzi\jsonBehavior\JsonValidator;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;
use yubundle\account\domain\v2\exceptions\ConfirmIncorrectCodeException;
use yubundle\account\domain\v2\helpers\ConfirmHelper;
use yubundle\account\domain\v2\helpers\LoginHelper;
use yubundle\account\domain\v2\validators\LoginValidator;

/**
 * Class ConfirmEntity
 *
 * @package yubundle\account\domain\v2\entities
 *
 * @property $login
 * @property $action
 * @property $code
 * @property $is_activated
 * @property $attempts
 * @property $data
 * @property $expire
 * @property $created_at
 */
class ConfirmEntity extends BaseEntity {

	protected $login;
	protected $action;
	protected $code;
	protected $is_activated = null;
    protected $attempts = 0;
	protected $data;
	protected $expire;
	protected $created_at;
	
	public function fieldType() {
		return [
			'created_at' => TimeValue::class,
		];
	}

    public function incrementAttepmts() {
        $this->attempts++;
    }

	public function setIsActivated($value) {
		if($this->is_activated == null) {
			$this->is_activated = $value;
		}
	}
	
	public function getIsActivated() {
		return $this->is_activated;
	}
	
	public function activate($code) {
		if($code != $this->code) {
			throw new ConfirmIncorrectCodeException();
		}
		$this->is_activated = true;
	}
	
	public function rules()
	{
		return [
			[['login', 'action', 'code'], 'trim'],
			[['login', 'action', 'code', 'expire'], 'required'],
			[['expire'], 'integer'],
			//['login', LoginValidator::class],
			//'normalizeLogin' => ['login', 'normalizeLogin'],
			[['code'], 'string', 'length' => 6],
			[['data'], JsonValidator::class],
		];
	}
	
	public function getActivationCode() {
		if(empty($this->code)) {
			$this->code = ConfirmHelper::generateCode();
		}
		return $this->code;
	}
	
	public function setLogin($value) {
		$this->login = LoginHelper::getPhone($value);
	}
	
}
