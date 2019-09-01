<?php

namespace yii2rails\extension\container;

use Psr\Container\ContainerInterface;
use yii2rails\extension\common\helpers\ClassHelper;

class Container implements ContainerInterface {

    private $services = [];

    public function __construct(array $definitions = [])
    {
        $this->addDefinitions($definitions);
    }

    public function __get($id) {
        return $this->services[$id];
    }

    public function get($id) {
        return $this->services[$id];
    }

    public function has($id) {
        return isset($this->services[$id]);
    }

    private function addInstance($id, $instance) {
        $this->services[$id] = $instance;
    }

    private function addDefinition(string $id, $definition) {
        if(is_object($definition)) {
            $instance = $definition;
        } else {
            $instance = ClassHelper::createObject($definition);
        }
        //$instance = ClassHelper::createObject($definition);
        $this->addInstance($id, $instance);
    }

    private function addDefinitions(array $definitions) {
        foreach ($definitions as $id => $definition) {
            $this->addDefinition($id, $definition);
        }
    }

}
