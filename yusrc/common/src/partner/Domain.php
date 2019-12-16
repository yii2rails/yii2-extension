<?php

namespace yubundle\common\partner;

/**
 * Class Domain
 * 
 * @property-read \yubundle\common\partner\interfaces\services\AuthInterface $auth
 * @property-read \yubundle\common\partner\interfaces\repositories\RepositoriesInterface $repositories
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [

			],
			'services' => [
                'auth',
			],
		];
	}

}
