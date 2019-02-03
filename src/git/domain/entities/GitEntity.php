<?php

namespace yii2rails\extension\git\domain\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\extension\yii\helpers\ArrayHelper;

/**
 * Class GitEntity
 * 
 * @package yii2rails\extension\git\domain\entities
 * 
 * property $id
 * @property $dir
 * @property RemoteEntity[] $remotes
 * @property BranchEntity[] $branches
 * @property TagEntity[] $tags
 * @property RefEntity[] $refs
 * property $local_head
 * property $orig_head
 */
class GitEntity extends BaseEntity {

	//protected $id;
	protected $dir;
	protected $remotes;
	protected $branches;
	protected $refs;
	protected $tags;
	/*protected $local_head;
	protected $orig_head;*/
	protected $is_send;
	
	public function fieldType() {
		return [
			'remotes' => [
				'type' => RemoteEntity::class,
				'isCollection' => true,
			],
			'branches' => [
				'type' => BranchEntity::class,
				'isCollection' => true,
			],
			'refs' => [
				'type' => RefEntity::class,
				'isCollection' => true,
			],
		];
	}
	
	public function getIsSend() {
		/** @var RefEntity $remoteRef */
		$remoteRef = ArrayHelper::findOne($this->refs, [
			'value' => 'refs/remotes/origin/master',
		]);
		/** @var RefEntity $ref */
		$ref = ArrayHelper::findOne($this->refs, [
			'value' => 'refs/heads/master',
		]);
		return $remoteRef->hash == $ref->hash;
	}
	
}
