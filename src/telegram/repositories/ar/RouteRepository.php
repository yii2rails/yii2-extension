<?php

namespace yii2rails\extension\telegram\repositories\ar;

use yii2rails\extension\telegram\interfaces\repositories\RouteInterface;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class RouteRepository
 * 
 * @package yii2rails\extension\telegram\repositories\ar
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 */
class RouteRepository extends BaseActiveArRepository implements RouteInterface {

	protected $schemaClass = true;

    public function tableName() {
        return 'telegram_route';
    }
}
