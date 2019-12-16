<?php

namespace yubundle\account\domain\v2\repositories\ar;

use Yii;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\enum\enums\TimeEnum;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\entities\SecurityEntity;
use yubundle\account\domain\v2\helpers\LoginHelper;
use yubundle\account\domain\v2\interfaces\repositories\RestorePasswordInterface;

class RestorePasswordRepository extends BaseRepository implements RestorePasswordInterface {

	const CONFIRM_ACTION = 'restore-password';
	
	public $smsCodeExpire = TimeEnum::SECOND_PER_HOUR;
	
	public function requestNewPassword($login, $mail = null) {
		$login = LoginHelper::getPhone($login);
		$entity = $this->domain->confirm->createNew($login, self::CONFIRM_ACTION, $this->smsCodeExpire);
		$message = Yii::t('account/restore-password', 'restore_password_sms {activation_code}', ['activation_code' => $entity->activation_code]);
		\App::$domain->notify->sms->send($login, $message);
	}
	
	public function checkActivationCode($login, $code) {
		return $this->domain->confirm->isVerifyCode($login, self::CONFIRM_ACTION, $code);
	}
	
	public function setNewPassword($login, $code, $password) {
		$login = LoginHelper::getPhone($login);
		/** @var LoginEntity $loginEntity */
		$loginEntity = $this->domain->login->oneByPhone($login);
		/** @var SecurityEntity $securityEntity */
		$securityEntity = $this->domain->security->oneById($loginEntity->id);
		$securityEntity->password_hash = Yii::$app->security->generatePasswordHash($password);
		$this->domain->security->updateById($securityEntity->id, $securityEntity);
		return $this->domain->confirm->delete($login, self::CONFIRM_ACTION);
	}
	
}