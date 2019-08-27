<?php

namespace yii2rails\extension\encrypt\helpers;

use Firebase\JWT\JWT;
use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\entities\KeyEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\RsaBitsEnum;
use yii2rails\extension\enum\base\BaseEnum;
use yii2rails\extension\jwt\entities\TokenEntity;
use UnexpectedValueException;
use yii2rails\extension\jwt\entities\JwtTokenEntity;
use yii2rails\extension\jwt\entities\ProfileEntity;

class JwtHelper {

    const DEFAULT_PROFILE = 'default';

    public static function sign(TokenEntity $tokenEntity, $profileName = self::DEFAULT_PROFILE, $keyId = null, $head = null) {
        $profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $keyId = $keyId ?  : StringHelper::genUuid();
        self::signToken($tokenEntity, $profileEntity, $keyId, $head);
        return $tokenEntity;
    }

    public static function decode($token, $profileName = self::DEFAULT_PROFILE) {
        $profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $tokenEntity = self::decodeToken($token, $profileEntity);
        $tokenEntity->token = $token;
        return $tokenEntity;
    }

    public static function decodeRaw($token, $profileName = self::DEFAULT_PROFILE) {
        $profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $decodedObject = self::tokenDecode($token);
        /*if (empty($profileEntity->key)) {
            throw new InvalidArgumentException('Key may not be empty');
        }*/
        self::validateHeader($decodedObject->header, $profileEntity);
        return $decodedObject;
        //return JwtHelper::decodeRaw($token, $profileEntity->key);
    }

    public static function decodeToken($token, JwtProfileEntity $profileEntity) {
        // todo: make select key (public or private)
        $key = $profileEntity->key->private;
        $decoded = JWT::decode($token, $key, $profileEntity->allowed_algs);
        $tokenEntity = new TokenEntity($decoded);
        return $tokenEntity;
    }

    public static function tokenDecode(string $jwt) : JwtTokenEntity {
        $parts = explode(SPC, $jwt);
        $token = count($parts) == 1 ? $parts[0] : $parts[1];
        $tks = explode('.', $token);
        $jwtTokenEntity = new JwtTokenEntity();
        $jwtTokenEntity->header = self::tokenDecodeItem($tks[0]);
        $jwtTokenEntity->payload = self::tokenDecodeItem($tks[1]);
        $jwtTokenEntity->sig = JWT::urlsafeB64Decode($tks[2]);
        return $jwtTokenEntity;
    }

    private static function tokenDecodeItem($data) {
        $jsonCode = JWT::urlsafeB64Decode($data);
        $object = JWT::jsonDecode($jsonCode);
        if (null === $object) {
            throw new UnexpectedValueException('Invalid encoding');
        }
        return $object;
    }

    private static function validateHeader($header, JwtProfileEntity $profileEntity) {
        $key = $profileEntity->key;
        if (empty($header->alg)) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        if (empty(JWT::$supported_algs[$header->alg])) {
            throw new UnexpectedValueException('Algorithm not supported');
        }
        if (!in_array($header->alg, $profileEntity->allowed_algs)) {
            throw new UnexpectedValueException('Algorithm not allowed');
        }
        if (is_array($key) || $key instanceof \ArrayAccess) {
            if (isset($header->kid)) {
                if (!isset($key[$header->kid])) {
                    throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
                }
                //$key = $key[$header->kid];
            } else {
                throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
            }
        }
    }

    private static function prepEntity(TokenEntity $tokenEntity, ProfileEntity $profileEntity) {
        if(!$tokenEntity->issuer_url && $profileEntity->issuer_url) {
            $tokenEntity->issuer_url = $profileEntity->issuer_url;
        }
    }

    public static function signToken(TokenEntity $tokenEntity, JwtProfileEntity $profileEntity, $keyId = null, $head = null) {
        if($profileEntity->audience) {
            $tokenEntity->audience = ArrayHelper::merge($tokenEntity->audience, $profileEntity->audience);
        }
        if(!$tokenEntity->expire_at && $profileEntity->life_time) {
            $tokenEntity->expire_at = TIMESTAMP + $profileEntity->life_time;
        }
        $data = self::entityToToken($tokenEntity);
        //$key = self::extractKey($profileEntity->key, 'private');
        //d($key);
        $tokenEntity->token = JWT::encode($data, $profileEntity->key->private, $profileEntity->default_alg, $keyId, $head);
    }

    private static function extractKey($key, $name) {
        if(is_array($key)) {
            $key = $key[$name];
        }
        return $key;
    }

    private static function entityToToken(TokenEntity $tokenEntity) {
        $data = $tokenEntity->toArray();
        $data = array_filter($data, function ($value) {return $value !== null;});
        //$data = $this->alias->encode($data);
        return $data;
    }

}
