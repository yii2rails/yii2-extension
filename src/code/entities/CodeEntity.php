<?php

namespace yii2rails\extension\code\entities;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\helpers\Helper;

/**
 * Class CodeEntity
 *
 * @package yii2rails\extension\code\entities
 *
 * @property string $fileName
 * @property string $fileExtension
 * @property string $namespace
 * @property ClassUseEntity[] $uses
 * @property string $code
 */
class CodeEntity extends BaseEntity {
	
	protected $fileName;
	protected $fileExtension = 'php';
	protected $namespace;
	protected $uses;
	protected $code;
	
	public function rules() {
		return [
			[['fileName'], 'required'],
		];
	}
	
	public function setUses($value) {
		$this->uses = Helper::forgeEntity($value, ClassUseEntity::class);
	}
}