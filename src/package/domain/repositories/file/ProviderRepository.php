<?php

namespace yii2lab\extension\package\domain\repositories\file;

use yii2lab\extension\arrayTools\repositories\base\BaseActiveDiscRepository;

/**
 * Class ProviderRepository
 * 
 * @package yii2lab\extension\package\domain\repositories\file
 * 
 * @property-read \yii2lab\extension\package\domain\Domain $domain
 */
class ProviderRepository extends BaseActiveDiscRepository /*implements GroupInterface*/ {

    protected $schemaClass = false;
    public $path = '@common/data';
    public $table = 'package_provider';

}
