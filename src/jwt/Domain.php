<?php

namespace yii2rails\extension\jwt;

use yii2rails\domain\enums\Driver;

/**
 * Class Domain
 * 
 * @package yii2rails\extension\jwt
 * @property-read \yii2rails\extension\jwt\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2rails\extension\jwt\interfaces\services\ProfileInterface $profile
 * @property-read \yii2rails\extension\jwt\interfaces\services\TokenInterface $token
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
                'token' => 'jwt',
                'profile' => Driver::ENV,
			],
			'services' => [
                'token',
                'profile',
			],
		];
	}
	
}