<?php

namespace yii2rails\extension\telegram\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class FromEntity
 * 
 * @package yii2rails\extension\telegram\entities
 * 
 * @property $id
 * @property $is_bot
 * @property $first_name
 * @property $username
 * @property $language_code
 */
class FromEntity extends BaseEntity {

	protected $id;
	protected $is_bot;
	protected $first_name;
	protected $username;
	protected $language_code;

}
