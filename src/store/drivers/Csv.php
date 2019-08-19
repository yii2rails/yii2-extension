<?php

namespace yii2rails\extension\store\drivers;

use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\store\interfaces\DriverInterface;

class Csv implements DriverInterface
{

    public function decode($content) {
        $content = trim($content);
        $lines = StringHelper::textToLines($content);
        $data = array_map('str_getcsv', $lines);
        return $data;
    }

    public function encode($data) {
        $content = '';
        foreach ($data as $columns) {
            $line = implode(',', $columns);
            $content .= $line . PHP_EOL;
        }
        $content = trim($content);
        return $content;
    }

}
