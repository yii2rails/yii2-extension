<?php

namespace yubundle\account\domain\v2\repositories\filedb;

use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;
use yubundle\account\domain\v2\interfaces\repositories\SecurityInterface;
use yubundle\account\domain\v2\repositories\traits\SecurityTrait;

class SecurityRepository extends BaseActiveFiledbRepository implements SecurityInterface {
	
	use SecurityTrait;
	
}