<?php

namespace yubundle\account\domain\v2\repositories\filedb;

use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yubundle\account\domain\v2\interfaces\repositories\LoginInterface;
use yubundle\account\domain\v2\repositories\traits\LoginTrait;

class LoginRepository extends BaseActiveFiledbRepository implements LoginInterface {
	
	use LoginTrait;
	
	protected $schemaClass = true;
	
}