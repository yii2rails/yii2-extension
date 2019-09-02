<?php

namespace yii2rails\extension\psr\container;

use yii2rails\extension\common\traits\classAttribute\MagicSetTrait;

class Container extends BaseContainer {

    use MagicSetTrait;

    public function setComponents(array $components) {
        $this->setDefinitions($components);
    }

    public function __get($id) {
        return $this->get($id);
    }

}
