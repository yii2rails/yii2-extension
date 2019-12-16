<?php

namespace yubundle\account\domain\v2\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\account\domain\v2\interfaces\repositories\IdentityInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class IdentityRepository
 * 
 * @package yubundle\account\domain\v2\repositories\ar
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
class IdentityRepository extends BaseActiveArRepository implements IdentityInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'user_login';
    }

}
