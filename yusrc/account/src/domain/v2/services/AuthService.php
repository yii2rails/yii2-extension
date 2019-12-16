<?php

namespace yubundle\account\domain\v2\services;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\services\base\BaseService;
use yii2rails\domain\traits\MethodEventTrait;
use yii2rails\extension\common\enums\StatusEnum;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\security\entities\SecurityConfigEntity;
use yii2rails\extension\security\exceptions\BannedException;
use yii2rails\extension\security\helpers\BlockAttemptHelper;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2rails\extension\yii\helpers\ArrayHelper;
use yubundle\account\domain\v2\behaviors\UserActivityFilter;
use yubundle\account\domain\v2\enums\AccountEventEnum;
use yubundle\account\domain\v2\events\AccountAuthenticationEvent;
use yubundle\account\domain\v2\filters\token\BaseTokenFilter;
use yubundle\account\domain\v2\filters\token\DefaultFilter;
use yubundle\account\domain\v2\forms\LoginForm;
use yubundle\account\domain\v2\helpers\AuthHelper;
use yubundle\account\domain\v2\helpers\TokenHelper;
use yubundle\account\domain\v2\interfaces\services\AuthInterface;
use yii\web\ServerErrorHttpException;
use yubundle\account\domain\v2\entities\LoginEntity;

/**
 * Class AuthService
 *
 * @package yubundle\account\domain\v2\services
 *
 * @property \yubundle\account\domain\v2\interfaces\repositories\AuthInterface $repository
 */
class AuthService extends BaseService implements AuthInterface {

	use MethodEventTrait;

    const LOGIN_ATTEMPT_KEY = 'account_auth_';

    public $failAuthTimeout = 2;
    public $rememberExpire = TimeEnum::SECOND_PER_DAY * 30;
    public $tokenAuthMethods = [
	    'bearer' => DefaultFilter::class,
    ];
	private $_identity = null;

	public function behaviors() {
		return [
			[
				'class' => UserActivityFilter::class,
				'methods' => ['authentication'],
			],
		];
	}

	public function oneSelf(Query $query = null) {
        $query = Query::forge($query);
        return \App::$domain->account->login->oneById($this->getIdentity()->id, $query);
    }
	
	public function isGuest() : bool {
		return Yii::$app->user->isGuest;
	}
 
	public function getIdentity() {
	    if(isset(Yii::$app->user)) {
            if(Yii::$app->user->isGuest) {
                $this->breakSession();
            }
            return Yii::$app->user->identity;
        }
        if($this->_identity === null) {
            $this->breakSession();
        }
        return $this->_identity;
	}
	
	public function authenticationFromApi(LoginForm $model) : LoginEntity {
		if(!$model->validate()) {
			throw new UnprocessableEntityHttpException($model);
		}
		$loginEntity = $this->authentication($model->login, $model->password);
        $this->checkStatus($loginEntity);
		return $loginEntity;
	}


    public function authenticationFromWeb(LoginForm $model) : LoginEntity {
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
		$loginEntity = $this->authentication($model->login, $model->password);
		$this->login($loginEntity, $model->rememberMe);
		return $loginEntity;
	}

	public function login(IdentityInterface $loginEntity, $rememberMe = false) {
        $this->checkStatus($loginEntity);
	    if(empty($loginEntity)) {
            return null;
        }
        $duration = $rememberMe ? $this->rememberExpire : 0;
        if(isset(Yii::$app->user)) {
            Yii::$app->user->login($loginEntity, $duration);
        }
        $this->_identity = $loginEntity;
        AuthHelper::setToken($loginEntity->token);
    }

	public function authenticationByToken($token, $type = null) {
		if(empty($token)) {
			throw new InvalidArgumentException('Empty token');
		}
        $loginEntity = $this->repository->authenticationByToken($token, $type);
		if(empty($loginEntity)) {
			$this->breakSession();
		}
		$this->checkStatus($loginEntity);
        $loginEntity->hideAttributes(['assignments', 'password', 'security']);
		return $loginEntity;
	}
	
	public function logout() {
		Yii::$app->user->logout();
		AuthHelper::setToken('');
	}
	
	public function denyAccess() {
		if(Yii::$app->user->getIsGuest()) {
			$this->breakSession();
		} else {
			throw new ForbiddenHttpException();
		}
	}
	
	public function loginRequired() {
		try {
			Yii::$app->user->loginRequired();
		} catch(InvalidConfigException $e) {
			return;
		}
	}
	
	public function breakSession() {
		if(APP == CONSOLE) {
			return;
		}
		if(APP == API) {
			throw new UnauthorizedHttpException;
		} else {
			$this->logout();
			Yii::$app->session->destroy();
			Yii::$app->response->cookies->removeAll();
			$this->loginRequired();
		}
	}
	
	public function checkOwnerId(BaseEntity $entity, $fieldName = 'user_id') {
		if($entity->{$fieldName} != \App::$domain->account->auth->identity->id) {
			throw new ForbiddenHttpException();
		}
	}

    private function checkStatus(IdentityInterface $loginEntity) {
        if (\App::$domain->account->login->isForbiddenByStatus($loginEntity->status)) {
            $error = new ErrorCollection;
            $error->add('login', 'account/login', 'status_disabled_message');
            throw new UnprocessableEntityHttpException($error);
        }
    }

    private function getLoginCache(string $cacheKey, int $timeout) {
        $loginCache = Yii::$app->cache->get($cacheKey);
        Yii::$app->cache->set($cacheKey, 123, $timeout);
        return $loginCache;
    }

    private function getSecurityConfig() {
        $securityConfig = EnvService::get('account.auth.security');
        $securityConfigEntity = new SecurityConfigEntity;
        $securityConfigEntity->loadConfig($securityConfig);
        return $securityConfigEntity;
    }

    private function checkLoginBan(string $login) {
        $securityConfigEntity = $this->getSecurityConfig();
        if($securityConfigEntity->attempt_count == null) {
            return;
        }
        $loginEntity = $this->domain->repositories->login->oneByVirtual($login);
        $securityKey = self::LOGIN_ATTEMPT_KEY . $loginEntity->login;
        try {
            BlockAttemptHelper::check($securityKey, $securityConfigEntity);
        } catch (BannedException $e) {
            $error = new ErrorCollection();
            $blockLeftMinute = round($e->getMessage() / TimeEnum::SECOND_PER_MINUTE);
            if($blockLeftMinute > 0) {
                $error->add('login', 'Ваш аккаунт заблокирован. Попробуйте зайти через '. $blockLeftMinute .' мин.');
                throw new UnprocessableEntityHttpException($error);
            }
        }
    }

    private function incrementLoginAttempt(string $login) {
        $securityConfigEntity = $this->getSecurityConfig();
        if($securityConfigEntity->attempt_count == null) {
            return;
        }
        $loginEntity = $this->domain->repositories->login->oneByVirtual($login);
        $securityKey = self::LOGIN_ATTEMPT_KEY . $loginEntity->login;
        try {
            return BlockAttemptHelper::increment($securityKey, $securityConfigEntity);
        } catch (BannedException $e) {
            $error = new ErrorCollection;
            $blockLeftMinute = round($e->getMessage() / TimeEnum::SECOND_PER_MINUTE);
            $error->add('login', 'Ваш аккаунт заблокирован на ' . $blockLeftMinute . ' мин.');
            throw new UnprocessableEntityHttpException($error);
        }
    }

    public function authentication($login, $password, $ip = null) {
        if(empty($ip)) {
            $ip = ClientHelper::ip();
        }
        //$loginEntity = $this->domain->repositories->login->oneByVirtual($login);
        $loginCache = $this->getLoginCache('user_attempt_' . $login, $this->failAuthTimeout);
        $this->checkLoginBan($login);

        $body = compact(['login', 'password']);
        $body = Helper::validateForm(LoginForm::class, $body);
        try {
            $loginEntity = $this->repository->authentication($body['login'], $body['password'], $ip);
        } catch(NotFoundHttpException $e) {
            $loginEntity = false;
        }
        if(!$loginEntity instanceof IdentityInterface || empty($loginEntity->id)) {
            if ($loginCache != false) {
                $error = new ErrorCollection;
                $error->add('password', 'account/auth', 'too_many_attempts');
                throw new UnprocessableEntityHttpException($error);
            }

            $attemptsLeft = $this->incrementLoginAttempt($login);
            if($attemptsLeft === null) {
                $error = new ErrorCollection;
                $error->add('password', 'account/auth', 'incorrect_login_or_password');
                throw new UnprocessableEntityHttpException($error);
            } else {
                $error = new ErrorCollection;
                $error->add('password', 'account/auth', 'incorrect_login_or_password {attemptsLeft}', ['attemptsLeft' => $attemptsLeft]);
                throw new UnprocessableEntityHttpException($error);
            }
        }
        $this->checkStatus($loginEntity);
        AuthHelper::setToken($loginEntity->token);

        $loginArray = $loginEntity->toArray();
        $loginArray['token'] = StringHelper::mask($loginArray['token']);
        $this->afterMethodTrigger(__METHOD__, [
            'login' => $login,
            'password' => StringHelper::mask($password, 0),
        ], $loginArray);
        $loginEntity->hideAttributes(['assignments', 'password', 'security']);
        $event = new AccountAuthenticationEvent;
        $event->identity = $loginEntity;
        $event->login = $login;
        $this->trigger(AccountEventEnum::AUTHENTICATION, $event);
        return $loginEntity;
    }

}
