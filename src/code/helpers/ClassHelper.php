<?php

namespace yii2rails\extension\code\helpers;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\code\entities\ClassEntity;
use yii2rails\extension\code\entities\ClassUseEntity;
use yii2rails\extension\code\entities\CodeEntity;
use yii2rails\extension\code\entities\InterfaceEntity;
use yii2rails\extension\code\render\ClassRender;
use yii2rails\extension\code\render\InterfaceRender;
use yii2rails\extension\yii\helpers\ArrayHelper;

/**
 * Class ClassHelper
 *
 * @package yii2rails\extension\code\helpers
 */
class ClassHelper
{
	
	public static function classNameToFileName($class) {
		$alias = str_replace(['\\', '/'], SL, $class);
		return \Yii::getAlias('@' . $alias);
	}
	
	public static function generate(BaseEntity $entity, $uses = []) {
		$codeEntity = new CodeEntity();
		/** @var ClassEntity|InterfaceEntity $entity */
		$codeEntity->fileName = $entity->namespace . DS . $entity->name;
		$codeEntity->namespace = $entity->namespace;
		$codeEntity->uses = Helper::forgeEntity($uses, ClassUseEntity::class);
		$codeEntity->code = self::render($entity);
		CodeHelper::save($codeEntity);
	}

    public static function render(BaseEntity $entity) {
		/** @var ClassRender|InterfaceRender $render */
		if($entity instanceof ClassEntity) {
			$render = new ClassRender();
		} elseif($entity instanceof InterfaceEntity) {
			$render = new InterfaceRender();
		}
		$render->entity = $entity;
		return $render->run();
	}
	
}
