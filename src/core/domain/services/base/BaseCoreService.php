<?php

namespace yii2rails\extension\core\domain\services\base;

use yii2rails\extension\core\domain\repositories\base\BaseCoreRepository;
use yii2rails\domain\services\base\BaseService;
use yii2rails\extension\common\helpers\ClassHelper;

/**
 * Class CoreBaseService
 *
 * @package yii2rails\domain\services
 *
 * @property BaseCoreRepository $repository
 */
class BaseCoreService extends BaseService {
	
	public $point = EMP;
	public $version = null;
	
	/**
	 * @var BaseCoreRepository
	 */
	protected $coreRepository;
	
	public function getRepository($name = null) {
		if(!isset($this->coreRepository)) {
			$this->coreRepository = ClassHelper::createObject([
				'class' => BaseCoreRepository::class,
				'domain' => $this->domain,
				'id' => $this->id,
				'point' => $this->point,
				'version' => $this->version,
			]);
		}
		return $this->coreRepository;
	}
	
}