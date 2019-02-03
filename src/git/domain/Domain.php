<?php

namespace yii2rails\extension\git\domain;

use yii2rails\domain\enums\Driver;

/**
 * Class Domain
 * 
 * @package yii2rails\extension\git\domain
 * @property-read \yii2rails\extension\git\domain\interfaces\services\GitInterface $git
 * @property-read \yii2rails\extension\git\domain\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2rails\extension\git\domain\interfaces\services\RemoteInterface $remote
 * @property-read \yii2rails\extension\git\domain\interfaces\services\BranchInterface $branch
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'git' => Driver::FILE,
			],
			'services' => [
				'git',
			],
		];
	}
	
}