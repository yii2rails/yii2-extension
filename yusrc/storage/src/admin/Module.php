<?php

namespace yubundle\storage\admin;

use yii\base\Module as YiiModule;
use domain\news\v1\enums\NewsPermissionEnum;
use yii2rails\extension\web\helpers\Behavior;
use yubundle\storage\domain\v1\enums\StoragePermissionEnum;

class Module extends YiiModule {

    public function behaviors()
    {
        return [
            'access' => Behavior::access(StoragePermissionEnum::MANAGE),
        ];
    }

}
