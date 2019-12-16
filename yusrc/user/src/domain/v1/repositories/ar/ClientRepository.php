<?php

namespace yubundle\user\domain\v1\repositories\ar;

use yubundle\user\domain\v1\interfaces\repositories\ClientInterface;
use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class ClientRepository
 * 
 * @package yubundle\user\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\user\domain\v1\Domain $domain
 */
class ClientRepository extends BaseActiveArRepository implements ClientInterface {

	//protected $schemaClass = true;

    public function tableName()
    {
        return 'portal_client';
    }
}
