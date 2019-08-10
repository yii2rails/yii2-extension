<?php

namespace yii2rails\extension\encrypt\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\extension\enum\base\BaseEnum;

/**
 * Class RsaKeyEntity
 * @package yii2rails\extension\encrypt\entities
 *
 * @property string $private
 * @property string $public
 * @property string $secret
 */
class RsaKeyEntity extends BaseEntity {

	protected $private;
    protected $public;
    protected $secret;

}
