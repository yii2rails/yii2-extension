<?php

namespace yii2rails\extension\store\drivers;

use yii2rails\extension\store\interfaces\DriverInterface;
use yii\helpers\ArrayHelper;

class Json implements DriverInterface
{

    public function decode($content) {
        $data = json_decode($content);
        $data = ArrayHelper::toArray($data);
        return $data;
    }

    public function encode($data) {
        $content = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	    $content = str_replace('    ', "\t", $content);
        return $content;
    }

}