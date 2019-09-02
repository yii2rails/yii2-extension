<?php

namespace yii2rails\extension\psr\container;

use yii2rails\extension\common\exceptions\NotFoundException;
use yii2rails\extension\common\helpers\ClassHelper;
use yii2rails\extension\yii\helpers\ArrayHelper;

abstract class BaseContainer implements ContainerInterface {

    private $components = [];

    public function __construct(array $components = [])
    {
        $this->setDefinitions($components);
    }

    public function get($id) : object {
        if(!$this->has($id)) {
            throw new NotFoundException('Component "' . $id . '" not found!');
        }
        if(!is_object($this->components[$id])) {
            $this->components[$id] = $this->ensureComponent($this->components[$id]);
        }
        return $this->components[$id];
    }

    public function has($id) : bool {
        return array_key_exists($id, $this->components);
    }

    protected function setDefinitions(array $definitions) {
        if($definitions) {
            $definitions = ArrayHelper::removeIfNull($definitions);
            foreach ($definitions as &$definition) {
                if (!is_object($definition)) {
                    $definition = $this->prepareDefinition($definition);
                }
            }
        }
        $this->components = $definitions;
    }

    protected function prepareDefinition($definition) {
        if(empty($definition)) {
            return $definition;
        }
        if(is_string($definition)) {
            $definition = ['class' => $definition];
        }
        return $definition;
    }

    protected function ensureComponent($component) : object {
        if(is_object($component)) {
            $instance = $component;
        } else {
            $instance = $this->createComponent($component);
        }
        return $instance;
    }

    protected function createComponent($component) : object {
        $instance = ClassHelper::createObject($component);
        return $instance;
    }

}
