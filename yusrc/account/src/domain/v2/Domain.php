<?php

namespace yubundle\account\domain\v2;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\common\enums\StatusEnum;
use yii2rails\extension\jwt\filters\token\JwtFilter;
use yubundle\account\domain\v2\enums\AccountRoleEnum;
use yii2rails\domain\enums\Driver;
use yii2rails\extension\enum\enums\TimeEnum;
use yubundle\account\domain\v2\filters\login\LoginValidator;
use yubundle\account\domain\v2\filters\token\DefaultFilter;
use yubundle\account\domain\v2\interfaces\services\LoginInterface;
use yubundle\account\domain\v2\interfaces\services\RegistrationInterface;
use yubundle\account\domain\v2\interfaces\services\RestorePasswordInterface;
use yubundle\account\domain\v2\services\SocketIOService;

// todo: описание докблоков в руководство

/**
 * Class Domain
 * 
 * @package yubundle\account\domain\v2
 * @property-read \yubundle\account\domain\v2\interfaces\services\AuthInterface $auth
 * @property-read \yubundle\account\domain\v2\interfaces\services\LoginInterface $login
 * @property-read \yubundle\account\domain\v2\interfaces\services\RegistrationInterface $registration
 * @property-read \yubundle\account\domain\v2\interfaces\services\TempInterface $temp
 * @property-read \yubundle\account\domain\v2\interfaces\services\RestorePasswordInterface $restorePassword
 * @property-read \yubundle\account\domain\v2\interfaces\services\SecurityInterface $security
 * @property-read \yubundle\account\domain\v2\interfaces\services\TestInterface $test
 * @property-read \yubundle\account\domain\v2\interfaces\services\RbacInterface $rbac
 * @property-read \yubundle\account\domain\v2\interfaces\services\ConfirmInterface $confirm
 * @property-read \yubundle\account\domain\v2\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yubundle\account\domain\v2\interfaces\services\TokenInterface $token
 * @property-read \yubundle\account\domain\v2\interfaces\services\JwtInterface $jwt
 * @property-read \yubundle\account\domain\v2\interfaces\services\ActivityInterface $activity
 * @property-read \yubundle\account\domain\v2\interfaces\services\OauthInterface $oauth
 * @property-read \yubundle\account\domain\v2\interfaces\services\SocketInterface $socket
 * @property-read \yubundle\account\domain\v2\interfaces\services\SocketInterface $socketio
 * @property-read \yubundle\account\domain\v2\interfaces\services\IdentityInterface $identity
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {

		$remoteServiceDriver = $this->primaryDriver == Driver::CORE ? Driver::CORE : null;
		//$serviceNamespace = $this->primaryDriver == Driver::CORE ? 'yubundle\account\domain\v2\services\core' : 'yubundle\account\domain\v2\services';
		if(EnvService::getServer('core.host')) {
            $remoteServiceDriver = Driver::CORE;
            $remoteRepositoryDriver = Driver::CORE;
        } else {
            $remoteServiceDriver = null;
            $remoteRepositoryDriver = Driver::ACTIVE_RECORD;
        }

        //$remoteRepositoryDriver = 'ldap';

		return [
			'repositories' => [
				'auth' => $remoteRepositoryDriver,
				'login' => $this->primaryDriver,
				//'temp' => Driver::ACTIVE_RECORD,
				'restorePassword' => $this->primaryDriver,
				'security' => $this->primaryDriver,
				'test' => Driver::FILEDB,
				//'rbac' => Driver::MEMORY,
				'confirm' => Driver::ACTIVE_RECORD,
				//'assignment' => $this->primaryDriver,
				'token' => Driver::ACTIVE_RECORD,
                'jwt' => 'jwt',
				'activity' => Driver::ACTIVE_RECORD,
                'identity' => Driver::ACTIVE_RECORD,
			],
			'services' => [
				'auth' => [
				    'failAuthTimeout' => 2,
					'rememberExpire' => TimeEnum::SECOND_PER_YEAR,
					'tokenAuthMethods' => [
						'bearer' => DefaultFilter::class,
						'jwt' => [
							'class' => JwtFilter::class,
							'profile' => 'auth',
						],
					],
				],
				'login' => [
					'defaultRole' => AccountRoleEnum::UNKNOWN_USER,
					//'defaultStatus' => 1,
					//'forbiddenStatusList' => [StatusEnum::DISABLE],
					'loginValidator' => LoginValidator::class,
				],
				'registration' => $remoteServiceDriver, //$serviceNamespace . '\RegistrationService',
				//'temp',
				'restorePassword' => $remoteServiceDriver,
				'security',
				'test',
				//'rbac',
				'confirm',
				//'assignment',
				'token',
                'jwt',
				'activity',
				'oauth',
                'socket',
                'socketio' => SocketIOService::class,
                //TODO: либо прописывать вот так, если не хотим явно указывать класс, но тогда и во FlowService надо менять
                //'socketIO',
                'identity',
			],
		];
	}
	
}