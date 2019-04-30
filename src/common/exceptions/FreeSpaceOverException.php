<?php

namespace yii2rails\extension\common\exceptions;

use yii\base\Exception;

class FreeSpaceOverException extends Exception
{

    public function getName()
    {
        return 'FreeSpaceOverException';
    }

}
