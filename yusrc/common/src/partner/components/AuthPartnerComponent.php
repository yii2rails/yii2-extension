<?php

namespace yubundle\common\partner\components;

use yii\base\Component;
use yii\web\UnauthorizedHttpException;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\jwt\entities\TokenEntity;
use yii2rails\extension\jwt\helpers\JwtHelper;
use yubundle\account\domain\v2\helpers\TokenHelper;
use yubundle\common\partner\enums\PartnerHttpHeaderEnum;

class AuthPartnerComponent extends Component {

	public function init()
    {
        if(\App::$domain->partner->auth->isAsCore()) {
            $token = \Yii::$app->request->headers->get(PartnerHttpHeaderEnum::AUTHORIZATION);
            $token = TokenHelper::extractToken($token);
            \App::$domain->partner->auth->authByToken($token);
            //d(\App::$domain->partner->auth->identity);
        }
    }

}
