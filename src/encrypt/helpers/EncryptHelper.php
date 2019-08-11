<?php

namespace yii2rails\extension\encrypt\helpers;

use yii2rails\extension\encrypt\entities\KeyEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\EncryptFunctionEnum;
use yii2rails\extension\enum\base\BaseEnum;

class EncryptHelper {

    public static function sign($msg, string $profileName)
    {
        $profile = ConfigProfileHelper::load($profileName);
        $key = $profile->key->type === OPENSSL_KEYTYPE_RSA ? $profile->key->private : $profile->key->private;
        $function = self::getFunction($profile->key);
        return SignatureHelper::sign($msg, $key, $function, $profile->algorithm);
    }

    public static function verify($msg, string $profileName, $signature)
    {
        $profile = ConfigProfileHelper::load($profileName);
        $key = $profile->key->type === OPENSSL_KEYTYPE_RSA ? $profile->key->public : $profile->key->private;
        $function = self::getFunction($profile->key);
        return SignatureHelper::verify($msg, $key, $signature, $function, $profile->algorithm);
    }

    private static function getFunction(KeyEntity $keyEntity) {
        return $keyEntity->type === OPENSSL_KEYTYPE_RSA ? EncryptFunctionEnum::OPENSSL : EncryptFunctionEnum::HASH_HMAC;;
    }

    public static function safeStrlen($str)
    {
        return mb_strlen($str, '8bit');
    }

}
