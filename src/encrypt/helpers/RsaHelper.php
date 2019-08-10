<?php

namespace yii2rails\extension\encrypt\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\encrypt\entities\RsaKeyEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\RsaBitsEnum;
use yii2rails\extension\enum\base\BaseEnum;

class RsaHelper {

    public static function load(string $profile) : RsaKeyEntity
    {
        $keyPairs = EnvService::get('encrypt.profiles.' . $profile);
        $rsaKeyEntity = new RsaKeyEntity;
        $rsaKeyEntity->private = ArrayHelper::getValue($keyPairs, 'private');
        $rsaKeyEntity->public = ArrayHelper::getValue($keyPairs, 'public');
        $rsaKeyEntity->secret = ArrayHelper::getValue($keyPairs, 'secret');
        return $rsaKeyEntity;
    }

    public static function generate(string $password = null, int $keyBits = RsaBitsEnum::BIT_2048, int $keyType = OPENSSL_KEYTYPE_RSA, $algorithm = EncryptAlgorithmEnum::SHA256) : RsaKeyEntity
    {
        $config = [
            "digest_alg" => $algorithm,
            "private_key_bits" => $keyBits,
            "private_key_type" => $keyType,
        ];
        $resource = openssl_pkey_new ( $config );
        $rsaKeyEntity = new RsaKeyEntity;
        $rsaKeyEntity->private = self::extractPrivateKey($resource, $password);
        $rsaKeyEntity->public = self::extractPublicKey($resource);
        return $rsaKeyEntity;
    }

    public static function extractPrivateKey($resource, string $password = null) : string {
        openssl_pkey_export ( $resource, $privateKey, $password);
        return $privateKey;
    }

    public static function extractPublicKey($resource) : string {
        $publicKey = openssl_pkey_get_details ( $resource );
        $publicKey = $publicKey["key"];
        return $publicKey;
    }

}
