<?php

namespace yubundle\reference\domain;

use yii2rails\domain\enums\Driver;

/**
 * Class Domain
 * 
 * @property-read \yubundle\reference\domain\interfaces\services\EnumInterface $enum
 * @property-read \yubundle\reference\domain\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yubundle\reference\domain\interfaces\services\ItemInterface $item
 * @property-read \yubundle\reference\domain\interfaces\services\BookInterface $book
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
                'enum' => Driver::ACTIVE_RECORD,
				'item' => Driver::ACTIVE_RECORD,
                'localization' => Driver::ACTIVE_RECORD,
                'book' => Driver::ACTIVE_RECORD,
			],
			'services' => [
				'item',
                'book',
			],
		];
	}
	
}