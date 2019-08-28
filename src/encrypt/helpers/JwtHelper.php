<?php

namespace yii2rails\extension\encrypt\helpers;

use Firebase\JWT\JWT;
use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\encrypt\entities\JwtHeaderEntity;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\entities\JwtTokenEntity;
use yii2rails\extension\encrypt\entities\KeyEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\RsaBitsEnum;
use yii2rails\extension\enum\base\BaseEnum;
use yii2rails\extension\jwt\entities\TokenEntity;
use UnexpectedValueException;
use yii2rails\extension\jwt\entities\ProfileEntity;

class JwtHelper {

    public static function sign(TokenEntity $tokenEntity, JwtProfileEntity $profileEntity, $keyId = null, $head = null) : string {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $keyId = $keyId ?  : StringHelper::genUuid();
        $token = self::signToken($tokenEntity, $profileEntity, $keyId, $head);
        return $token;
    }

    public static function decode($token, JwtProfileEntity $profileEntity) : TokenEntity {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $tokenEntity = self::decodeToken($token, $profileEntity);
        $tokenEntity->token = $token;
        return $tokenEntity;
    }

    public static function decodeRaw($token, JwtProfileEntity $profileEntity) : JwtTokenEntity {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $jwtTokenEntity = self::tokenDecode($token);
        /*if (empty($profileEntity->key)) {
            throw new InvalidArgumentException('Key may not be empty');
        }*/
        self::validateHeader($jwtTokenEntity->header, $profileEntity);
        return $jwtTokenEntity;
    }

    public static function decodeToken(string $token, JwtProfileEntity $profileEntity) : TokenEntity {
        // todo: make select key (public or private)
        $key = $profileEntity->key->private;
        $decoded = JWT::decode($token, $key, $profileEntity->allowed_algs);
        $tokenEntity = new TokenEntity((array)$decoded);
        return $tokenEntity;
    }

    public static function tokenDecode(string $jwt) : JwtTokenEntity {
        $parts = explode(SPC, $jwt);
        $token = count($parts) == 1 ? $parts[0] : $parts[1];
        $tks = explode('.', $token);
        $jwtTokenEntity = new JwtTokenEntity();
        $jwtTokenEntity->header = self::tokenDecodeItem($tks[0]);
        $jwtTokenEntity->payload = self::tokenDecodeItem($tks[1]);
        $jwtTokenEntity->sig = Base64Helper::urlSafeDecode($tks[2]);
        return $jwtTokenEntity;
    }

    private static function tokenDecodeItem(string $data) {
        $jsonCode = Base64Helper::urlSafeDecode($data);
        $object = JWT::jsonDecode($jsonCode);
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
        if (empty(JWT::$supported_algs[$headerEntity->alg])) {
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

    private static function signToken(TokenEntity $tokenEntity, JwtProfileEntity $profileEntity, string $keyId = null, $head = null) : string {
        if($profileEntity->audience) {
            $tokenEntity->audience = ArrayHelper::merge($tokenEntity->audience, $profileEntity->audience);
        }
        if(!$tokenEntity->expire_at && $profileEntity->life_time) {
            $tokenEntity->expire_at = TIMESTAMP + $profileEntity->life_time;
        }
        $data = self::entityToToken($tokenEntity);
        return JWT::encode($data, $profileEntity->key->private, $profileEntity->default_alg, $keyId, $head);
    }

    private static function entityToToken(TokenEntity $tokenEntity) {
        $data = $tokenEntity->toArray();
        $data = array_filter($data, function ($value) {return $value !== null;});
        //$data = $this->alias->encode($data);
        return $data;
    }

}
