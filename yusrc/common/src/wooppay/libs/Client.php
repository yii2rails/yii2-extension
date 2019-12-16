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
use yubundle\common\wooppay\enums\MethodEnum;
use yubundle\common\wooppay\exceptions\BlockIpException;
use yubundle\common\wooppay\exceptions\CancelOperationException;
use yubundle\common\wooppay\models\ConfigModel;
use yubundle\common\wooppay\request\AuthRequest;
use yubundle\common\wooppay\request\MobileOperatorRequest;
use yubundle\common\wooppay\request\Request;
use yubundle\common\wooppay\request\ServiceFieldsRequest;
use yubundle\common\wooppay\request\ServiceNameRequest;
use yubundle\common\wooppay\response\Response;
use yubundle\common\wooppay\stores\CacheStore;
use yubundle\common\wooppay\stores\StoreInterface;

class Client
{

    private $identity = null;
    private $transfer;
    private $config;
    private $store;

    public function __construct(ConfigModel $configModel) {
        $this->config = $configModel;
        $this->transfer = new Transfer($configModel->url);
        $this->store = new $this->config->storeClass;
        $this->initAuth();
    }

    public function serviceIdByName($serviceName) {
        $request  = new ServiceNameRequest;
        $request->service_name = $serviceName;
        $response = $this->sendRequest(MethodEnum::GET_SERVICE_ID_BY_NAME, $request);
        return intval($response->response->serviceId);
    }

    public function serviceFields($serviceName) {
        $request  = new ServiceFieldsRequest;
        $request->service = $serviceName;
        $response = $this->sendRequest(MethodEnum::GET_SERVICE_FIELDS, $request);
        $data = json_decode($response->response->data);
        return $data;
    }

    public function getMobileOperator($phone) {
        $request  = new MobileOperatorRequest;
        $request->phone = $phone;
        $response = $this->sendRequest(MethodEnum::GET_MOBILE_OPERATOR, $request);
        return $response->response->operator;
    }

    public function validateServiceFields($serviceName, $data = []) {
        $serviceId = $this->serviceIdByName($serviceName);
        $request  = new ServiceFieldsRequest;
        $request->service = $serviceName;
        $request->serviceId = $serviceId;
        $request->merchant = null;
        $request->fields = $data;
        $response = $this->sendRequest(MethodEnum::CHECK_SERVICE_FIELDS, $request);
        if($response->error_code == 0) {
            return true;
        }
        return $response;
    }

    public function balance() {
        $response = $this->sendRequest(MethodEnum::GET_BALANCE);
        return floatval($response->response->amount);
    }

    private function getStoreInstance() : StoreInterface {
        $class = $this->config->storeClass;
        $store = new $class;
        return $store;
    }

    private function authentication() {
        $request = new AuthRequest;
        $request->username = $this->config->username;
        $request->password = $this->config->password;
        $response = $this->transfer->sendRequest(MethodEnum::LOGIN, $request);
        $cookies = $this->transfer->getCookies();
        $this->store->set('session', $cookies);
        $this->identity = $response->response;
        return $response;
    }

    private function initAuth() {
        if($this->identity) {
            return;
        }
        $cookies = $this->store->get('session');
        if($cookies) {
            $this->transfer->setCookies($cookies);
        }
        //d($this->client->__getFunctions());
        //d($this->client->__getTypes());
    }

    private function sendRequest(string $method, Request $request = null) : Response {
        try {
            $response = $this->transfer->sendRequest($method, $request);
        } catch (UnauthorizedHttpException $e) {
            if(!$this->config->autoAuth) {
                throw new UnauthorizedHttpException;
            }
            $this->authentication();
            $response = $this->transfer->sendRequest($method, $request);
        }
        return $response;
    }

}
