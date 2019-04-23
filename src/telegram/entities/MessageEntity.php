<?php

namespace yii2rails\extension\telegram\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class MessageEntity
 * 
 * @package yii2rails\extension\telegram\entities
 * 
 * @property $id
 * @property $from
 * @property $chat
 * @property $date
 * @property $text
 */
class MessageEntity extends BaseEntity {

	protected $id;
	protected $from;
	protected $chat;
	protected $date;
	protected $text;

}
