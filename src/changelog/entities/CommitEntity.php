<?php

namespace yii2rails\extension\changelog\entities;

use yii2rails\domain\BaseEntity;

/**
 * Class CommitEntity
 * 
 * @package yii2rails\extension\changelog\entities
 * 
 * @property $type
 * @property $scope
 * @property $subject
 * @property $task_id
 * @property $source_text
 * @property WordEntity $class
 */
class CommitEntity extends BaseEntity {

	protected $type;
	protected $scope;
	protected $subject;
    protected $task_id;
    protected $source_text;
    protected $class;

}
