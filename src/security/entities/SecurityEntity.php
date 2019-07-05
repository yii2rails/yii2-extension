<?php

namespace yii2rails\extension\security\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class SecurityConfigEntity
 * @package yii2rails\extension\security\entities
 *
 * @property $expire
 * @property $attempt_count
 */
class SecurityEntity extends BaseEntity {

    protected $expire;
    protected $attempt_count;

}
