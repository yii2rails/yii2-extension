<?php

namespace yii2rails\extension\web\helpers;

use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\web\enums\HttpMethodEnum;

/*
env-local:

'cors' => [
    'origin' => [
        'http://host.project',
    ],
    'credentials' => true,
],
 */

class CorsHelper {

    public static function autoload() {
        $origin = ArrayHelper::getValue($_SERVER, 'HTTP_ORIGIN');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: ' . ArrayHelper::getValue($_SERVER, 'HTTP_ACCESS_CONTROL_REQUEST_HEADERS'));
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD, PATCH, TRACE, CONNECT');
        //header('Access-Control-Allow-Credentials: true');
        //header('Access-Control-Expose-Headers: Content-Type, Link, Access-Token, Authorization, Time-Zone, X-Pagination-Total-Count, X-Pagination-Page-Count, X-Pagination-Current-Page, X-Pagination-Per-Page, X-Entity-Id, X-Agent-Fingerprint');
        if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit;
        }
    }

	public static function generate($origin = null) {
		/*if(empty($origin)) {
			$origin = self::generateOriginFromEnvUrls();
		}*/
        $origin = ArrayHelper::getValue($_SERVER, 'HTTP_ORIGIN');
        //$origin = \Yii::$app->request->headers->get('Origin');
		$origin = EnvService::get('cors.origin', $origin);
        $origin = ArrayHelper::toArray($origin);
		return [
			'class' => Cors::class,
			'cors' => [
				'Origin' => $origin,
				'Access-Control-Request-Method' => HttpMethodEnum::values(),
				'Access-Control-Request-Headers' => [
					HttpHeaderEnum::CONTENT_TYPE,
					HttpHeaderEnum::X_REQUESTED_WITH,
					HttpHeaderEnum::AUTHORIZATION,
					HttpHeaderEnum::TIME_ZONE,
                    HttpHeaderEnum::X_ENTITY_ID,
                    HttpHeaderEnum::X_AGENT_FINGERPRINT,
				],
				'Access-Control-Expose-Headers' => [
                    HttpHeaderEnum::CONTENT_TYPE,
					HttpHeaderEnum::LINK,
					HttpHeaderEnum::ACCESS_TOKEN,
					HttpHeaderEnum::AUTHORIZATION,
					HttpHeaderEnum::TIME_ZONE,
					HttpHeaderEnum::TOTAL_COUNT,
					HttpHeaderEnum::PAGE_COUNT,
					HttpHeaderEnum::CURRENT_PAGE,
					HttpHeaderEnum::PER_PAGE,
                    HttpHeaderEnum::X_ENTITY_ID,
                    HttpHeaderEnum::X_AGENT_FINGERPRINT,
				],
				'Access-Control-Allow-Credentials' => EnvService::get('cors.credentials', false),
				//'Access-Control-Max-Age' => 3600, // Allow OPTIONS caching
			],
		];
	}

}
