<?php

namespace yii2rails\extension\package\domain\repositories\filedb;

use yii2rails\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yii2rails\extension\package\domain\interfaces\repositories\GroupInterface;
use yii2rails\extension\yii\helpers\FileHelper;

/**
 * Class GroupRepository
 * 
 * @package yii2rails\extension\package\domain\repositories\filedb
 * 
 * @property-read \yii2rails\extension\package\domain\Domain $domain
 */
class GroupRepository extends BaseActiveDiscRepository implements GroupInterface {

	protected $schemaClass = true;
	public $path = '@common/data';
	public $table = 'package_group';
	
	public function init() {
	    $appDataPath = \Yii::getAlias($this->path);
	    if(!FileHelper::has($appDataPath)) {
            $this->path = '@vendor/yii2rails/yii2-extension/src/package/domain/data';
        }
	}
	
}
