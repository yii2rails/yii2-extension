<?php

namespace yii2rails\extension\changelog\repositories\disc;

use yii2rails\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yii2rails\extension\changelog\interfaces\repositories\TypeInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class TypeRepository
 * 
 * @package yii2rails\extension\changelog\repositories\disc
 * 
 * @property-read \yii2rails\extension\changelog\Domain $domain
 */
class TypeRepository extends BaseActiveDiscRepository implements TypeInterface {

	protected $schemaClass = true;

    public $table = 'type';
    public $path = '@yii2rails/extension/changelog/data';

}
