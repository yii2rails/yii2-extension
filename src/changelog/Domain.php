<?php

namespace yii2rails\extension\changelog;

use yii2rails\domain\enums\Driver;

/**
 * Class Domain
 * 
 * @property-read \yii2rails\extension\changelog\interfaces\services\TypeInterface $type
 * @property-read \yii2rails\extension\changelog\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2rails\extension\changelog\interfaces\services\WordInterface $word
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
                'type' => Driver::DISC,
                'word' => Driver::DISC,
			],
			'services' => [
                'type',
                'word',
			],
		];
	}
	
}