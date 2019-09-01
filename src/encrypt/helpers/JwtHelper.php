<?php

namespace yii2rails\extension\encrypt\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\Alias;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\encrypt\entities\JwtEntity;
use yii2rails\extension\encrypt\entities\JwtHeaderEntity;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\entities\JwtTokenEntity;
use yii2rails\extension\encrypt\entities\KeyEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\encrypt\enums\RsaBitsEnum;
use yii2rails\extension\enum\base\BaseEnum;
use UnexpectedValueException;

class JwtHelper {

    public static function forgeBySubject(array $subject, JwtProfileEntity $profileEntity, $keyId = null) : JwtEntity {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = $subject;
        $jwtEntity->token = self::sign($jwtEntity, $profileEntity, $keyId);
        return $jwtEntity;
    }

    public static function sign(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity, $keyId = null) : string {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $keyId = $keyId ?  : StringHelper::genUuid();
        $token = self::signToken($jwtEntity, $profileEntity, $keyId);
        return $token;
    }

    public static function decode(string $token, JwtProfileEntity $profileEntity) : JwtEntity {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $tokenDto = JwtEncodeHelper::decode($token);
        JwtEncodeHelper::verifyTokenDto($tokenDto, $profileEntity);
        $jwtEntity = new JwtEntity($tokenDto->payload);
        $jwtEntity->token = $token;
        return $jwtEntity;
    }

    public static function decodeRaw(string $jwt, JwtProfileEntity $profileEntity) : JwtTokenEntity {
        $tokenDto = JwtEncodeHelper::decode($jwt);
        $jwtTokenEntity = new JwtTokenEntity;
        $jwtTokenEntity->header = (array) $tokenDto->header;
        $jwtTokenEntity->payload = $tokenDto->payload;
        $jwtTokenEntity->sig = $tokenDto->signature;
        return $jwtTokenEntity;
    }

    private static function tokenDecodeItem(string $data) {
        $jsonCode = SafeBase64Helper::decode($data);
        $object = JwtJsonHelper::decode($jsonCode);
        if (null === $object) {
            throw new UnexpectedValueException('Invalid encoding');
        }
        return (array) $object;
    }

    private static function validateHeader(JwtHeaderEntity $headerEntity, JwtProfileEntity $profileEntity) {
        $key = $profileEntity->key;
        if (empty($headerEntity->alg)) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        if (!JwtAlgorithmEnum::isSupported($headerEntity->alg)) {
            throw new UnexpectedValueException('Algorithm not supported');
        }
        if (!in_array($headerEntity->alg, $profileEntity->allowed_algs)) {
            throw new UnexpectedValueException('Algorithm not allowed');
        }
        if (is_array($key) || $key instanceof \ArrayAccess) {
            if (isset($headerEntity->kid)) {
                if (!isset($key[$headerEntity->kid])) {
                    throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
                }
                //$key = $key[$headerEntity->kid];
            } else {
                throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
            }
        }
    }

    private static function signToken(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity, string $keyId = null) : string {
        if($profileEntity->audience) {
            $jwtEntity->audience = ArrayHelper::merge($jwtEntity->audience, $profileEntity->audience);
        }
        if(!$jwtEntity->expire_at && $profileEntity->life_time) {
            $jwtEntity->expire_at = TIMESTAMP + $profileEntity->life_time;
        }
        $data = self::entityToToken($jwtEntity);

        $jwtHeaderEntity = new JwtHeaderEntity;
        $jwtHeaderEntity->alg = $profileEntity->default_alg;
        $jwtHeaderEntity->kid = $keyId;

        return JwtEncodeHelper::encode($data, $profileEntity->key->private, $jwtHeaderEntity);
    }

    private static function entityToToken(JwtEntity $jwtEntity) : array {
        $data = $jwtEntity->toArray();
        $data = array_filter($data, function ($value) {return $value !== null;});
        $data = self::encodeAliases($data);
        return $data;
    }

    private static function encodeAliases(array $data) : array {
        $alias = new Alias;
        $alias->setAliases([
            'issuer_url' => 'iss',
            'subject_url' => 'sub',
            'audience' => 'aud',
            'expire_at' => 'exp',
            'begin_at' => 'nbf',
        ]);
        $data = $alias->encode($data);
        return $data;
    }

}
