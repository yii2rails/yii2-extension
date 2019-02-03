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
        list($controllerName, $actionName) = explode('/', $command);
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