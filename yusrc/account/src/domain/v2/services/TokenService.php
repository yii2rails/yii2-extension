<?php

namespace yubundle\account\domain\v2\services;

use yii2rails\extension\web\helpers\ClientHelper;
use yubundle\account\domain\v2\interfaces\services\TokenInterface;
use Exception;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\services\base\BaseService;
use yubundle\account\domain\v2\entities\TokenEntity;
use yubundle\account\domain\v2\helpers\TokenHelper;

class TokenService extends BaseService implements TokenInterface {

    public $profile = 'auth';

    public function forge($userId, $ip, $profile = null)
    {
        $subject = [
            'id' => $userId,
        ];
        $profile = $profile ? $profile : $this->profile;
        $tokenEntity = \App::$domain->jwt->token->forgeBySubject($subject, $profile);
        return 'jwt '  . $tokenEntity->token;
    }

    public function validate($token, $ip = null)
    {
        if($ip == null) {
            $ip = ClientHelper::ip();
        }
        $tokenDto = TokenHelper::forgeDtoFromToken($token);
        try {
            $jwtTokenEntity =  \App::$domain->jwt->token->decode($tokenDto->token, $this->profile);
        } catch (Exception $e) {
            throw new UnauthorizedHttpException($e->getMessage());
        }
        $tokenEntity = new TokenEntity;
        $tokenEntity->token = $jwtTokenEntity->token;
        $tokenEntity->user_id = $jwtTokenEntity->subject['id'];
        return $tokenEntity;
    }

}
