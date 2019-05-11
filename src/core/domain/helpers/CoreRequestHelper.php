<?php

namespace yii2rails\extension\core\domain\helpers;

use Yii;
use yii\base\InvalidConfigException;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2module\account\domain\v3\helpers\AuthHelper;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii2rails\extension\common\helpers\UrlHelper;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2lab\rest\domain\exceptions\UnavailableRestServerHttpException;
use yii2lab\rest\domain\helpers\RestHelper;

class CoreRequestHelper {
	
	public static function sendRequest(RequestEntity $requestEntity) {
        $requestEntity->headers = CoreHelper::getHeaders();

        /*$storageJwtProfile = EnvService::get('jwt.profiles.storage');
        if(!empty($storageJwtProfile)) {
            $userId = \App::$domain->account->auth->identity->id;
            $subject = [
                'id' => $userId,
            ];
            $tokenEntity = \App::$domain->jwt->token->forgeBySubject($subject, 'storage');
        }*/

        $responseEntity = RestHelper::sendRequest($requestEntity);
        try {
            self::handleStatusCode($responseEntity);
        } catch (UnauthorizedHttpException $e) {
            \App::$domain->account->auth->logout();
        }
        return $responseEntity;
	}

    protected static function handleStatusCode(ResponseEntity $responseEntity) {
        if($responseEntity->is_ok) {
            if($responseEntity->status_code == 201 || $responseEntity->status_code == 204) {
                $responseEntity->content = null;
            }
        } else {
            if($responseEntity->status_code >= 400) {
                self::showUserException($responseEntity);
            }
            if($responseEntity->status_code >= 500) {
                if($responseEntity->status_code >= 503) {
                    throw new UnavailableRestServerHttpException();
                }
                self::showServerException($responseEntity);
            }
        }
    }

    protected static function showServerException(ResponseEntity $responseEntity) {
        throw new ServerErrorHttpException();
    }

    protected static function showUserException(ResponseEntity $responseEntity) {
        $statusCode = $responseEntity->status_code;
        if($statusCode == 401) {
            throw new UnauthorizedHttpException();
        } elseif($statusCode == 403) {
            throw new ForbiddenHttpException();
        } elseif($statusCode == 422) {
            throw new UnprocessableEntityHttpException($responseEntity->data);
        } elseif($statusCode == 404) {
            throw new NotFoundHttpException(get_called_class());
        }
    }

}
