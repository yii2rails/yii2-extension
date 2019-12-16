<?php

return [
	'error' => 'yii2bundle\error\module\Module',
    'rbac' => \yii2bundle\rbac\admin\helpers\ModuleHelper::config(),

    'vendor' => 'yii2tool\vendor\admin\Module',
    'notify' => 'yii2lab\notify\admin\Module',
    'user' => 'yubundle\account\web\BackendModule',

	'dashboard' => 'yii2bundle\dashboard\admin\Module',
    'storage' => 'yubundle\storage\admin\Module',
];
