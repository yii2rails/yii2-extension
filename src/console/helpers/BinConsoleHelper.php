<?php

namespace yii2rails\extension\console\helpers;

use yii\helpers\Inflector;

class BinConsoleHelper
{

    public $controllerNamespace;

    public function __construct($controllerNamespace) {
        $this->controllerNamespace = $controllerNamespace;
    }

    public function init() {
        $args = \yii2rails\extension\console\helpers\ArgHelper::all();
        $command = \yii2rails\extension\yii\helpers\ArrayHelper::firstKey($args);
        $commandArray = explode(SL, $command);
        $controllerName = isset($commandArray[0]) ? $commandArray[0] : 'default';
        $actionName = isset($commandArray[1]) ? $commandArray[1] : 'index';
        self::runAction($controllerName, $actionName);
    }

    private function runAction($controllerName, $actionName) {
        $controllerName = \yii\helpers\Inflector::camelize($controllerName);
        $actionName = \yii\helpers\Inflector::camelize($actionName);
        $controllerClass = $this->controllerNamespace . '\\' . $controllerName . 'Controller';
        $controllerClass = \yii2rails\extension\common\helpers\ClassHelper::normalizeClassName($controllerClass);
        $controllerInstance = new $controllerClass;
        $action = 'action' . $actionName;
        $controllerInstance->$action();
    }

}