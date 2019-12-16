<?php

namespace yubundle\account\domain\v2\repositories\ar;

use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\account\domain\v2\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveArRepository {
	
	use SecurityTrait;
	
    public function tableName() {
        return 'user_login';
    }

    public function fieldAlias() {
        return [
            'token' => 'remember_token',
            'password_hash' => 'password',
        ];
    }

}
