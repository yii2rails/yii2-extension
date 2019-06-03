<?php

namespace yii2rails\extension\package\domain\services;

use yii2rails\extension\package\domain\entities\ConfigEntity;
use yii2rails\extension\package\domain\interfaces\services\ConfigInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ConfigService
 * 
 * @package yii2rails\extension\package\domain\services
 * 
 * @property-read \yii2rails\extension\package\domain\Domain $domain
 * @property-read \yii2rails\extension\package\domain\interfaces\repositories\ConfigInterface $repository
 */
class ConfigService extends BaseActiveService implements ConfigInterface {
	
	public function oneByDir(string $dir) : ConfigEntity {
		return $this->repository->oneByDir($dir);
	}
	
}
