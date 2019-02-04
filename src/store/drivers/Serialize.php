<?php

namespace yii2rails\extension\store\drivers;

use yii2rails\extension\store\interfaces\DriverInterface;

class Serialize implements DriverInterface
{

    public function decode($content) {
        $data = unserialize($content);
        //$data = ArrayHelper::toArray($data);
        return $data;
    }

    public function encode($data) {
        $content = serialize($data);
        return $content;
    }

}