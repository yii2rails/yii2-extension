<?php

namespace yubundle\account\domain\v2\repositories\tps;

use yubundle\account\domain\v2\interfaces\repositories\TokenInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class TokenRepository
 * 
 * @package yubundle\account\domain\v2\repositories\tps
 * 
 * @property-read \yubundle\account\domain\v2\Domain $domain
 */
class TokenRepository extends BaseRepository implements TokenInterface {

	protected $schemaClass;

}
