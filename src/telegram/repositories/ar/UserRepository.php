<?php

namespace yii2rails\extension\telegram\repositories\ar;

use yii2rails\extension\telegram\interfaces\repositories\UserInterface;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class UserRepository
 * 
 * @package yii2rails\extension\telegram\repositories\ar
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 */
class UserRepository extends BaseActiveArRepository implements UserInterface {

	protected $schemaClass = true;
	protected $primaryKey = null;

    public function tableName() {
        return 'telegram_user';
    }

}
