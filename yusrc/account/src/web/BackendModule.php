<?php

namespace yubundle\account\web;

/**
 * user module definition class
 */
class BackendModule extends Module
{

    public $controllerMap = [
        'auth' => [
            'class' => 'yubundle\account\web\controllers\AuthController',
            'layout' => '@yii2lab/applicationTemplate/backend/views/layouts/singleForm.php',
        ],
    ];

}
