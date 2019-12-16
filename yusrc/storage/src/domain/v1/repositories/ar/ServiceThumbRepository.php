<?php

namespace yubundle\storage\domain\v1\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\storage\domain\v1\interfaces\repositories\ServiceThumbInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class ServiceThumbRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class ServiceThumbRepository extends BaseActiveArRepository implements ServiceThumbInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'storage_service_thumb';
    }
}
