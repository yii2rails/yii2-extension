<?php

namespace yii2rails\extension\core\domain;

use yii2rails\domain\enums\Driver;

/**
 * Class Domain
 *
 * @package yii2rails\extension\core\domain
 *
 * @deprecated
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'client' => Driver::REST,
			],
			'services' => [
				'client',
			],
		];
	}
	
}