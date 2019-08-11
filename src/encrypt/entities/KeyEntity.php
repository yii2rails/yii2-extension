<?php

namespace yii2rails\extension\encrypt\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\EncryptFunctionEnum;
use yii2rails\extension\enum\base\BaseEnum;

/**
 * Class KeyEntity
 * @package yii2rails\extension\encrypt\entities
 *
 * @property string $type
 * @property string $private
 * @property string $public
 * @property string $secret
 */
class KeyEntity extends BaseEntity {

	protected $type = null;
	protected $private;
    protected $public;
    protected $secret;

    /*public function getType() {
        if(!empty($this->type)) {
            return $this->type;
        }
        if(!empty($this->secret)) {
            return EncryptFunctionEnum::HASH_HMAC;
        }
        if(!empty($this->private) || !empty($this->public)) {
            return EncryptFunctionEnum::OPENSSL;
        }
    }*/
}
