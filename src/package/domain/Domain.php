<?php

namespace yii2rails\extension\package\domain;

use yii2rails\domain\enums\Driver;
use yii2rails\domain\helpers\DomainHelper;

/**
 * Class Domain
 * 
 * @package yii2rails\extension\package\domain
 * @property-read \yii2rails\extension\package\domain\interfaces\services\PackageInterface $package
 * @property-read \yii2rails\extension\package\domain\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2rails\extension\package\domain\interfaces\services\GroupInterface $group
 * @property-read \yii2rails\extension\package\domain\interfaces\services\ConfigInterface $config
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function init() {
		parent::init();
		/*DomainHelper::forgeDomains([
			'git' => 'yii2rails\extension\git\domain\Domain',
		]);*/
	}
	
	public function config() {
		return [
			'repositories' => [
				'package' => Driver::FILE,
				'group' => Driver::FILEDB,
                'provider' => Driver::FILE,
				'config' => Driver::FILE,
			],
			'services' => [
				'package',
				'group',
				'config',
			],
		];
	}
	
}