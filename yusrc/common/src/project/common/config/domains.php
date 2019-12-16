<?php

return [
    'account' => \yubundle\account\domain\v2\helpers\DomainHelper::config(),
    'lang' => 'yii2bundle\lang\domain\Domain',
    'notify' => 'yii2lab\notify\domain\Domain',
    'reference' => 'yubundle\reference\domain\Domain',
    'user' => 'yubundle\user\domain\v1\Domain',

    'package' =>  'yii2rails\extension\package\domain\Domain',
    'vendor' =>  \yubundle\common\vendor\domain\helpers\DomainHelper::config(),

    'navigation' => 'yii2bundle\navigation\domain\Domain',
    'jwt' => 'yii2rails\extension\jwt\Domain',
    'guide' => 'yii2tool\guide\domain\Domain',
    'geo' => 'yii2bundle\geo\domain\Domain',
    'storage' => 'yubundle\storage\domain\v1\Domain',

    'rbac' => 'yii2bundle\rbac\domain\Domain',
    'partner' => 'yubundle\common\partner\Domain',
    'staff' => 'yubundle\staff\domain\v1\Domain',

];
