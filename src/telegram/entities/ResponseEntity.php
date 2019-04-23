<?php

namespace yii2rails\extension\telegram\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class ResponseEntity
 * 
 * @package yii2rails\extension\telegram\entities
 * 
 * @property $message
 * @property $message_text
 */
class ResponseEntity extends BaseEntity {

	protected $message;
	protected $message_text;

}
