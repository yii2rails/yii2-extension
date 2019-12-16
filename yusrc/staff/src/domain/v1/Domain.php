<?php

namespace yubundle\staff\domain\v1;

use yii2rails\domain\enums\Driver;
use yubundle\reference\domain\services\EnumService;

/**
 * Class Domain
 * 
 * @package yubundle\staff\domain\v1
 * @property-read \yubundle\staff\domain\v1\interfaces\services\DivisionInterface $division
 * @property-read \yubundle\staff\domain\v1\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yubundle\staff\domain\v1\interfaces\services\WorkerInterface $worker
 * @property-read \yubundle\staff\domain\v1\interfaces\services\CompanyInterface $company
 * @property-read \yubundle\staff\domain\v1\interfaces\services\PostInterface $post
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
			    'division' => Driver::ACTIVE_RECORD,
                'worker' => Driver::ACTIVE_RECORD,
                'company' => Driver::ACTIVE_RECORD,
			],
			'services' => [
			    'division',
                'worker',
                'company',
                'post',
			],
		];
	}
	
}