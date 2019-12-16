<?php

namespace yubundle\common\wooppay\factories;

use yii2rails\app\domain\helpers\EnvService;
use yubundle\common\wooppay\libs\Client;
use yubundle\common\wooppay\models\ConfigModel;
use yubundle\common\wooppay\stores\CacheStore;

class ClientFactory
{

    public static function createClient() : Client
    {
        $config = new ConfigModel;
        $config->url = EnvService::get('wooppay.wsdl.url');
        $config->username = EnvService::get('wooppay.wsdl.username');
        $config->password = EnvService::get('wooppay.wsdl.password');
        $config->storeClass = CacheStore::class;
        $config->autoAuth = true;
        $wooppayWsdlClient = new Client($config);
        return $wooppayWsdlClient;
    }

}

/*
$wooppayClient = ClientFactory::createClient();

$fields = $wooppayClient->serviceFields('beeline');
d($fields);

$fields = $wooppayClient->validateServiceFields('beeline', [
    'account' => '+7 (705) 111-22-33',
    'amount' => '10',
]);
d($fields);

$balance = $wooppayClient->balance();
d($balance);
*/
