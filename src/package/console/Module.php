<?php

namespace yii2rails\extension\package\console;

use yii\base\Module as YiiModule;
use yii2rails\domain\helpers\DomainHelper;

class Module extends YiiModule {
	
	public function init() {
		DomainHelper::forgeDomains([
			'package' => 'yii2rails\extension\package\domain\Domain',
		]);
		parent::init();
	}
	
}
