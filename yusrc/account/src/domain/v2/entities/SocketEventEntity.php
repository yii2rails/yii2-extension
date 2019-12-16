<?php

namespace yubundle\account\domain\v2\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class SocketEventEntity
 * 
 * @package yubundle\account\domain\v2\entities
 *
 * @property $name
 * @property $user_id
 * @property $data
 */
class SocketEventEntity extends BaseEntity {

    protected $name;
	protected $user_id;
    protected $data;

}
