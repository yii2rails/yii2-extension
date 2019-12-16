<?php

namespace yubundle\common\partner\services;

use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yubundle\account\domain\v2\helpers\TokenHelper;
use yubundle\common\partner\entities\IdentityEntity;
use yubundle\common\partner\interfaces\services\AuthInterface;
use yii2rails\domain\services\base\BaseService;
use yii\web\UnauthorizedHttpException;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\jwt\entities\TokenEntity;
use yii2rails\extension\jwt\helpers\JwtHelper;

/**
 * Class AuthService
 * 
 * @package yubundle\common\partner\services
 * 
 * @property-read \yubundle\common\partner\Domain $domain
 * @property-read \yubundle\common\partner\interfaces\repositories\AuthInterface $repository
 */
class AuthService extends BaseService implements AuthInterface {

    /**
     * @var IdentityEntity
     */
    private $_identity = null;
    private $_selfToken = null;

    /**
     * @return bool
     */
    public function isAsCore() : bool {
        return EnvService::getServer('core.host') === null;
    }

    /**
     * @param $token
     * @return IdentityEntity
     * @throws UnauthorizedHttpException
     */
    public function authByToken($token) : IdentityEntity {
        $token = TokenHelper::extractToken($token);
        if(empty($token)) {
            throw new UnauthorizedHttpException('Empty partner token');
        }
        $tokenEntityVerifed = $this->verifyToken($token);
        $identityEntity = new IdentityEntity;
        $identityEntity->login = ArrayHelper::getValue($tokenEntityVerifed, 'subject.name');
        //$identityEntity->token = $token;
        $identityEntity->status = 1;
        $this->_identity = $identityEntity;
        return $identityEntity;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function forgeSelfToken() : string {
        if(empty($this->_selfToken)) {
            $partnerName = $this->selfPartnerName();
            $subject = [
                'name' => $partnerName,
            ];
            $jwtProfileName = $this->selfPartnerJwtProfileName();
            $tokenEntity = \App::$domain->jwt->token->forgeBySubject($subject, $jwtProfileName);
            $this->_selfToken = $tokenEntity->token;
        }
        return $this->_selfToken;
    }

    protected function isAsPartner() : bool {
        return !$this->isAsCore();
    }

    protected function verifyToken(string $token) : TokenEntity {
        $jwtProfileName = $this->profileNameFromTokenEntity($token);
        try {
            $tokenEntity = \App::$domain->jwt->token->decode($token, 'partner_' . $jwtProfileName);
            return $tokenEntity;
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Partner authorization fail', 0, $e);
        }
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function selfPartnerName() : string {
        $partnerName = EnvService::getServer('core.partnerName');
        if(empty($partnerName)) {
            throw new InvalidArgumentException('Section "core.partnerName" not defined!');
        }
        return $partnerName;
    }

    /**
     * @param string $token
     * @return string
     * @throws UnauthorizedHttpException
     */
    protected function profileNameFromTokenEntity(string $token) : string {
        try {
            $tokenData = JwtHelper::tokenDecode($token);
        } catch (\DomainException $e) {
            throw new UnauthorizedHttpException('Partner token not valid', 0, $e);
        }
        $partnerName = $tokenData->payload->subject->name;
        return $partnerName;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function selfPartnerJwtProfileName() : string {
        $partnerName = $this->selfPartnerName();
        return 'partner_' . $partnerName;
    }

}
