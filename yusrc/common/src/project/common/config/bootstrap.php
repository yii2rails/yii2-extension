<?php

Yii::$container->set('yii\web\ErrorHandler', [
	'class' => 'yii2bundle\error\domain\web\ErrorHandler',
	/*'filters' => [
		'yii2bundle\rbac\domain\filters\PermissionException',
	],*/
]);
