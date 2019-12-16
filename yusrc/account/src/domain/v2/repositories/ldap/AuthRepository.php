<?php

namespace yubundle\account\domain\v2\repositories\ldap;

use App;
use tests\unit\helpers\RegistryTest;
use Yii;
use yii2rails\domain\data\Query;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\common\enums\StatusEnum;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\web\helpers\ClientHelper;
use yubundle\account\domain\v2\chain\AccountHandler;
use yubundle\account\domain\v2\helpers\TokenHelper;
use yubundle\account\domain\v2\interfaces\repositories\AuthInterface;
use yubundle\account\domain\v2\helpers\LDAPHelper;

use yii\helpers\ArrayHelper;

class AuthRepository extends BaseRepository implements AuthInterface {

    public function authentication($login, $password, $ip = null) {
        $post = [
            'login' => $login,
            'password' => $password,
        ];
        $ldapHelper = new LDAPHelper();
        $ldapData = $ldapHelper->login($login, $password);
        $authenticationHandler = new AccountHandler();
        $ldapData['login'] = $login;
        $ldapData['password'] = $password;
        $ldapData['ip'] = $ip;
        $loginEntity = $authenticationHandler->get($ldapData);
        return $loginEntity;
    }

    public function authenticationByToken($token, $type = null, $ip = null) {
        try {
            $loginEntity = TokenHelper::authByToken($token, $this->domain->auth->tokenAuthMethods);
        } catch(NotFoundHttpException $e) {
            throw new UnauthorizedHttpException();
        }
        return $loginEntity;
    }
}
