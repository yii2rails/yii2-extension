<?php

namespace yii2rails\extension\telegram\repositories\ar;

use yii2rails\extension\telegram\interfaces\repositories\BotInterface;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class BotRepository
 * 
 * @package yii2rails\extension\telegram\repositories\ar
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 */
class BotRepository extends BaseActiveArRepository implements BotInterface {

	protected $schemaClass = true;

	public function tableName() {
        return 'telegram_bot';
    }
}
