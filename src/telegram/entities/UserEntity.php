<?php

namespace yii2rails\extension\telegram\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class UserEntity
 * 
 * @package yii2rails\extension\telegram\entities
 * 
 * @property $id
 * @property $bot_id
 * @property $user_id
 * @property $username
 * @property $first_name
 * @property $is_bot
 * @property $state
 * @property $session
 * @property $settings
 */
class UserEntity extends BaseEntity {

	protected $id;
    protected $bot_id;
    protected $user_id;
	protected $username;
	protected $first_name;
	protected $is_bot;
	protected $state = 'default';
	protected $session = '{}';
	protected $settings = '{}';

}
