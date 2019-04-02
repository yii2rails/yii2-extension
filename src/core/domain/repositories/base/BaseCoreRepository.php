<?php

namespace yii2rails\extension\core\domain\repositories\base;

use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii2bundle\rest\domain\repositories\base\BaseRestRepository;
use yii2rails\extension\core\domain\helpers\CoreHelper;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\entities\ResponseEntity;

class BaseCoreRepository extends BaseRestRepository {
	
	public $version = null;
	public $point = EMP;
	
	public function init() {
		parent::init();
		//$this->initVersion();
		$this->initBaseUrl();
//		$this->initHeaders();
	}
	
	protected function sendRequest(RequestEntity $requestEntity) : ResponseEntity {
		$headers = $requestEntity->headers;
		$headers[ClientHelper::IP_HEADER_KEY] = ClientHelper::getIpFromRequest();
		$requestEntity->headers = $headers;
		$responseEntity = parent::sendRequest($requestEntity);
		return $responseEntity;
	}

    protected function normalizeRequestEntity(RequestEntity $requestEntity) {
        $this->headers = ArrayHelper::merge($this->headers, CoreHelper::getHeaders());
        return parent::normalizeRequestEntity($requestEntity);
    }
	
	protected function showServerException(ResponseEntity $responseEntity) {
		$data = $responseEntity->data;
		$type = ArrayHelper::getValue($data, 'type');
		if(class_exists($type)) {
			$previous = new $type($data['message']);
			$previous = $previous instanceof \Exception ? $previous : null;
			throw new ServerErrorHttpException('Core Error' , 0, $previous);
		}
		throw new ServerErrorHttpException('Core Error: ' . $data['message'], 0);
	}
	
	protected function showUserException(ResponseEntity $responseEntity) {
		$statusCode = $responseEntity->status_code;
		if($statusCode == 422) {
			throw new UnprocessableEntityHttpException($responseEntity->data);
		}
		if($statusCode == 401) {
			try {
				\App::$domain->account->auth->breakSession();
			} catch(UnauthorizedHttpException $e) {
				$message = ArrayHelper::getValue($responseEntity->data, 'message');
				throw new UnauthorizedHttpException($message, 0, $e);
			}
			return;
		}
		parent::showUserException($responseEntity);
	}
	
	protected function initBaseUrl() {
		$this->baseUrl = CoreHelper::forgeUrl($this->version, $this->point);
	}
	
	/*private function initHeaders() {
		$this->headers = ArrayHelper::merge($this->headers, CoreHelper::getHeaders());
	}

    private function initVersion() {
	    if(empty($this->version)) {
		    //$this->version = CoreHelper::defaultApiVersionNumber(1);
	    }
    }*/
}
