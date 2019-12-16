<?php

namespace yubundle\account\domain\v2\services;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\services\base\BaseActiveService;
use yii2lab\notify\domain\exceptions\SmsTimeLimitException;
use yii2rails\domain\values\TimeValue;
use yii2rails\extension\enum\enums\TimeEnum;
use yubundle\account\domain\v2\entities\ConfirmAttemptEntity;
use yubundle\account\domain\v2\entities\ConfirmEntity;
use yubundle\account\domain\v2\enums\AccountConfirmActionEnum;
use yubundle\account\domain\v2\exceptions\ConfirmAlreadyExistsException;
use yubundle\account\domain\v2\exceptions\ConfirmAttemptException;
use yubundle\account\domain\v2\exceptions\ConfirmIncorrectCodeException;
use yubundle\account\domain\v2\helpers\ConfirmHelper;
use yubundle\account\domain\v2\helpers\LoginHelper;
use yubundle\account\domain\v2\interfaces\services\ConfirmInterface;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\common\exceptions\CreatedHttpExceptionException;

/**
 * Class ConfirmService
 *
 * @package yubundle\account\domain\v2\services
 * @property \yubundle\account\domain\v2\interfaces\repositories\ConfirmInterface $repository
 */
class ConfirmService extends BaseActiveService implements ConfirmInterface
{
	
	public function delete($login, $action)
	{
		$login = LoginHelper::getPhone($login);
		$this->beforeAction(self::EVENT_DELETE);
		$this->repository->cleanAll($login, $action);
		return $this->afterAction(self::EVENT_DELETE);
	}
	
	public function isActivated($login, $action)
	{
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action);
		return $confirmEntity->is_activated;
	}
	
	public function activate($login, $action, $code)
	{
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action);
		$this->checkAttempt($login, $action, $code);
		$confirmEntity->activate($code);
		$this->repository->update($confirmEntity);
		//return $this->isActivated($login, $action);
	}
	
	public function verifyCode($login, $action, $code)
	{
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action);
        $this->checkAttempt($login, $action, $code);
		if($confirmEntity->code != $code) {
			throw new ConfirmIncorrectCodeException(Yii::t('account/confirm', 'incorrect_code'));
		}
		return $confirmEntity;
	}

	private function checkAttempt($login, $action, $code) {
        $confirmEntity = $this->oneByLoginAndAction($login, $action);
        if(!$this->isVerifyCode($login, $action, $code)) {
            $confirmEntity->incrementAttepmts();
        }
        $this->repository->update($confirmEntity);
	    if($confirmEntity->attempts >= 5) {
            $this->repository->deleteByLoginAndAction($confirmEntity->login, $confirmEntity->action);
            throw new ConfirmAttemptException(Yii::t('account/confirm', 'confirm_code_attempt_error'));
        }
    }

	public function isVerifyCode($login, $action, $code)
	{
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action);
		if($confirmEntity->code != $code) {
			return false;
		}
		return true;
	}
	
	public function isHas($login, $action)
	{
		try {
			$this->oneByLoginAndAction($login, $action);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	/**
	 * @param $login
	 * @param $action
	 *
	 * @return ConfirmEntity
	 * @throws NotFoundHttpException
	 */
	private function oneByLoginAndAction($login, $action)
	{
		$login = LoginHelper::getPhone($login);
		$this->cleanOld($login, $action);
		try {
            return $this->repository->oneByLoginAndAction($login, $action);
        } catch (NotFoundHttpException $e) {
		    throw new NotFoundHttpException(Yii::t('account/confirm', 'not_found'), 0, $e);
        }
	}
	
	public function send($login, $action, $expire, $data = null)
	{
        $timeout = EnvService::get('account.confirm.smsInterval', 60);
        $passwordRestoreCache = $this->getCache('user-' . $action . '-' . $login, $timeout);

        if ($passwordRestoreCache != false) {
            $min = round($timeout / TimeEnum::SECOND_PER_MINUTE);
            throw new CreatedHttpExceptionException(Yii::t('account/restore-password', 'password_reset_expire {min}', ['min' => $min]));
        } else {
            /** @var ConfirmEntity $confirmEntity */
            try {
                $confirmEntity = $this->repository->oneByLoginAndAction($login, $action);
                if ($this->isExpired($confirmEntity)) {
                    $confirmEntity = $this->createNew($login, $action, $expire, $data);
                }
            } catch (NotFoundHttpException $e) {
                $confirmEntity = $this->createNew($login, $action, $expire, $data);
            }
            $this->sendSms($login, $confirmEntity->code);
        }
	}
	
	protected function createNew($login, $action, $expire, $data = null)
	{
		$login = LoginHelper::getPhone($login);
		$this->cleanOld($login, $action);
		$entityArray['login'] = $login;
		$entityArray['action'] = $action;
		$entityArray['data'] = $data;
		$entityArray['expire'] = TIMESTAMP + $expire;
		$entityArray['code'] = ConfirmHelper::generateCode();
		if($this->isHas($login, $action)) {
			throw new ConfirmAlreadyExistsException();
		}
		return parent::create($entityArray);
	}
	
	private function cleanOld($login, $action)
	{
		$login = LoginHelper::getPhone($login);
		$this->repository->cleanOld($login, $action);
	}
	
	protected function sendSms($login, $activation_code)
	{
		$login = LoginHelper::pregMatchLogin($login);
		$loginParts = LoginHelper::splitLogin($login);
		$message = Yii::t('account/confirm', 'confirmation_code {code}', ['code' => $activation_code]);
		try {
			\App::$domain->notify->sms->send($loginParts['country_code'] . $loginParts['phone'], $message);
		} catch(SmsTimeLimitException $e) {
			throw new ConfirmAlreadyExistsException;
		}
	}

    private function getCache(string $cacheKey, int $timeout) {
        $passwordRestoreCache = Yii::$app->cache->get($cacheKey);
        Yii::$app->cache->set($cacheKey, 123, $timeout);
        return $passwordRestoreCache;
    }

    private function isExpired(ConfirmEntity $confirmEntity) {
        $expiredDate = new TimeValue();
        $expiredDate->set($confirmEntity->expire);
        $todayDate = new TimeValue();
        $todayDate->setNow();
        if ($todayDate > $expiredDate) {
            return true;
        } else {
            return false;
        }
    }
}
