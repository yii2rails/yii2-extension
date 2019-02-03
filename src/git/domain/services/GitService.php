<?php

namespace yii2rails\extension\git\domain\services;

use yii2rails\extension\git\domain\interfaces\services\GitInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class GitService
 * 
 * @package yii2rails\extension\git\domain\services
 * 
 * @property-read \yii2rails\extension\git\domain\Domain $domain
 * @property-read \yii2rails\extension\git\domain\interfaces\repositories\GitInterface $repository
 */
class GitService extends BaseActiveService implements GitInterface {
	
	public function oneByDir($dir) {
		return $this->repository->oneByDir($dir);
	}
	
}
