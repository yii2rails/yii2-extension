<?php

use yii\helpers\ArrayHelper;
use yii2rails\domain\helpers\DomainHelper;
use yii2rails\extension\telegram\libs\AppLib;
use yii2rails\extension\telegram\helpers\AppHelper;

DomainHelper::forgeDomains([
    'telegram' => 'yii2rails\extension\telegram\Domain',
]);

$botToken = ArrayHelper::getValue($_GET, 'token');

if(empty($botToken)) {
    throw new \Exception('Empty bot token!');
}
$app = new AppLib($botToken);
$routeCollection = \App::$domain->telegram->route->allByBotId($app->botId, $app->userEntity->state);
$routes = AppHelper::forgeRoutesFromRouteCollection($routeCollection);
$app->setRoutes($routes);
$app->run();
