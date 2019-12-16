<?php

namespace yubundle\common\partner\interfaces\services;
use yii\base\InvalidArgumentException;
use yii\web\UnauthorizedHttpException;
use yubundle\common\partner\entities\IdentityEntity;

/**
 * Interface AuthInterface
 * 
 * @package yubundle\common\partner\interfaces\services
 *
 * @property-read IdentityEntity $identity
 *
 * @property-read \yubundle\common\partner\Domain $domain
 * @property-read \yubundle\common\partner\interfaces\repositories\AuthInterface $repository
 */
interface AuthInterface {

    /**
     * @return bool
     */
    public function isAsCore() : bool;

    /**
     * @param $token
     * @return IdentityEntity
     * @throws UnauthorizedHttpException
     */
    public function authByToken($token) : IdentityEntity;

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function forgeSelfToken() : string;

}
