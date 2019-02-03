<?php

namespace yii2rails\extension\core\domain\repositories\rest;

use Yii;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;
use yii2lab\rest\domain\repositories\base\BaseRestRepository;

/**
 * Class ClientRepository
 *
 * @package yii2rails\extension\core\domain\repositories\rest
 *
 * @deprecated
 */
class ClientRepository extends BaseRestRepository {
	
	public function getBaseUrl() {
		$baseUrl = EnvService::get('servers.core.domain');
		if(YII_ENV_TEST) {
			$baseUrl .= 'index-test.php/';
		}
		return trim($baseUrl, SL);
	}
	
	protected function showServerException($response) {
		$exception = $response->data['type'];
		if(YII_DEBUG) {
			throw new $exception($response->data['message']);
		}
		parent::showServerException($response);
	}
	
	protected function showUserException($response) {
		$statusCode = $response->statusCode;
		if($statusCode == 422) {
			throw new UnprocessableEntityHttpException($response->data);
		}
		parent::showUserException($response);
	}
}
