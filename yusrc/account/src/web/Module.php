<?php

namespace yubundle\account\web;

use Yii;
use yii\base\Module as YiiModule;

/**
 * user module definition class
 */
class Module extends YiiModule
{
	
	public function beforeAction($action)
	{
		$controllerId = Yii::$app->controller->id;
		$moduleId = 'account';
		Yii::$app->view->title = Yii::t($moduleId . SL . $controllerId, 'title');
		\App::$domain->navigation->breadcrumbs->create([$moduleId . SL . 'main', 'title']);
		\App::$domain->navigation->breadcrumbs->create([$moduleId . SL . $controllerId, 'title']);

		return parent::beforeAction($action);
	}

}
