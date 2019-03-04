<?php

use yii2rails\app\domain\helpers\Env;

$name = 'console';
$path = '../../../../../..';

@include_once(__DIR__ . '/' . $path . '/vendor/yii2rails/yii2-app/src/App.php');

if(!class_exists(App::class)) {
    die('Run composer install');
}

App::initPhpApplication($name);
