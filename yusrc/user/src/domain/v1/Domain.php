<?php

namespace yubundle\user\domain\v1;

use yii2rails\domain\enums\Driver;

/**
 * Class Domain
 * 
 * @package yubundle\user\domain\v1
 *
 * 
 * @property-read \yubundle\user\domain\v1\interfaces\services\PersonInterface $person
 * @property-read \yubundle\user\domain\v1\interfaces\services\ClientInterface $client
 * @property-read \yubundle\user\domain\v1\interfaces\services\AddressInterface $address
 * @property-read \yubundle\user\domain\v1\interfaces\repositories\RepositoriesInterface $repositories
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
			    'person' => Driver::ACTIVE_RECORD,
                'client' => Driver::ACTIVE_RECORD,
			],
			'services' => [
			    'person',
                'client',
                'address',
                'avatar',
			],
		];
	}
	
}