<?php

namespace yubundle\storage\domain\v1\exceptions;

use yii\base\Exception;

class FreeSpaceOverException extends Exception
{

    public function getName()
    {
        return 'FreeSpaceOverException';
    }

}
