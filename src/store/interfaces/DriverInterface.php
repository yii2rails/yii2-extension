<?php

namespace yii2rails\extension\store\interfaces;

interface DriverInterface
{

    public function decode($content);
    public function encode($data);

}