<?php

namespace yii2rails\extension\changelog\repositories\disc;

use yii2rails\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yii2rails\extension\changelog\interfaces\repositories\WordInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class WordRepository
 * 
 * @package yii2rails\extension\changelog\repositories\disc
 * 
 * @property-read \yii2rails\extension\changelog\Domain $domain
 */
class WordRepository extends BaseActiveDiscRepository implements WordInterface {

	protected $schemaClass = true;

    public $table = 'word';
    public $path = '@yii2rails/extension/changelog/data';

}
