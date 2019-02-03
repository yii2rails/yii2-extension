<?php

namespace yii2rails\extension\package\domain\repositories\file;

use yii2rails\extension\arrayTools\repositories\base\BaseActiveDiscRepository;

/**
 * Class ProviderRepository
 * 
 * @package yii2rails\extension\package\domain\repositories\file
 * 
 * @property-read \yii2rails\extension\package\domain\Domain $domain
 */
class ProviderRepository extends BaseActiveDiscRepository /*implements GroupInterface*/ {

    protected $schemaClass = false;
    public $path = '@common/data';
    public $table = 'package_provider';

}
