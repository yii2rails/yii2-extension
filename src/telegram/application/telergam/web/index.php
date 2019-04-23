<?php

use yii2rails\domain\base\BaseDomainLocator;

$name = 'console';
$path = '../..';

@include_once(__DIR__ . '/' . $path . '/vendor/yii2rails/yii2-app/src/App.php');

if(!class_exists(App::class)) {
    die('Run composer install');
}

App::initPhpApplication($name);
App::$domain = new BaseDomainLocator;

include(__DIR__ . '/' . $path . '/vendor/yii2rails/yii2-extension/src/telegram/libs/bootstrap.php');
