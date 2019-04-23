<?php

namespace yii2rails\extension\telegram;

use yii2rails\domain\enums\Driver;

/**
 * Class Domain
 * 
 * @package yii2rails\extension\telegram
 * @property-read \yii2rails\extension\telegram\interfaces\services\BotInterface $bot
 * @property-read \yii2rails\extension\telegram\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2rails\extension\telegram\interfaces\services\RouteInterface $route
 * @property-read \yii2rails\extension\telegram\interfaces\services\ActionInterface $action
 * @property-read \yii2rails\extension\telegram\interfaces\services\UserInterface $user
 * @property-read \yii2rails\extension\telegram\interfaces\services\ResponseInterface $response
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
                'bot' => Driver::ACTIVE_RECORD,
                'route' => Driver::ACTIVE_RECORD,
                'action' => Driver::ACTIVE_RECORD,
                'user' => Driver::ACTIVE_RECORD,
                //'response' => 'telegram',
                'response' => 'mock',
			],
			'services' => [
                'bot',
                'route',
                'action',
                'user',
                'response',
			],
		];
	}
	
}