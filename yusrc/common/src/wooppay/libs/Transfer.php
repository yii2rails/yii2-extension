<?php

namespace yubundle\common\wooppay\libs;

use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii2rails\extension\code\entities\ClassEntity;
use yii2rails\extension\code\entities\ClassVariableEntity;
use yii2rails\extension\code\helpers\ClassHelper;
use yubundle\common\wooppay\exceptions\BlockIpException;
use yubundle\common\wooppay\exceptions\CancelOperationException;
use yubundle\common\wooppay\request\AuthRequest;
use yubundle\common\wooppay\request\MobileOperatorRequest;
use yubundle\common\wooppay\request\Request;
use yubundle\common\wooppay\request\ServiceFieldsRequest;
use yubundle\common\wooppay\request\ServiceNameRequest;
use yubundle\common\wooppay\response\Response;

class Transfer
{

    private $client;

    public function __construct($url) {
        $this->client = new \SoapClient($url);
    }

    public function getCookies() {
        return $this->client->_cookies;
    }

    public function setCookies($cookies) {
        $this->client->_cookies = $cookies;
    }

    public function sendRequest(string $method, Request $request = null) : Response {
        $callable = [$this->client, $method];
        $params = [];
        if($request) {
            if(!$request->validate()) {
                throw new \yii2rails\domain\exceptions\UnprocessableEntityHttpException($request);
            }
            $params[] = $request;
        }
        $response = call_user_func_array($callable, $params);
        $responseModel = new Response($response);
        $this->errorHandle($responseModel);
        return $responseModel;
    }

    private function errorHandle($response) {
        $hasError = property_exists($response, 'error_code');
        if(!$hasError || $response->error_code == 0) {
            return;
        }
        if($response->error_code == 1) {
            throw new ForbiddenHttpException('Forbidden access for method');
        }
        if($response->error_code == 3) {
            throw new ServerErrorHttpException('Internal server error: ' . json_encode($response));
        }
        if($response->error_code == 5) {
            throw new UnauthorizedHttpException();
        }
        if($response->error_code == 101) {
            throw new UnprocessableEntityHttpException('Bad username or password');
        }
        if($response->error_code == 213) {
            throw new NotFoundHttpException('Service not found');
        }
        if($response->error_code == 214) {
            throw new CancelOperationException('Operation canceled! Reason: security validation');
        }
        if($response->error_code == 309) {
            throw new CancelOperationException('Operation canceled! Reason: bank processing');
        }
        if($response->error_code == 308) {
            throw new BlockIpException('You IP address blocked');
        }
        if($response->error_code == 411) {
            throw new UnprocessableEntityHttpException('Empty any attribute');
        }
        throw new ServerErrorHttpException('Internal server error: ' . json_encode($response));
    }

}
