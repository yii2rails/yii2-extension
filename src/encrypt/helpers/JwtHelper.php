<?php

namespace yii2rails\extension\encrypt\helpers;

use Firebase\JWT\JWT;
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
use yii2rails\extension\encrypt\enums\RsaBitsEnum;
use yii2rails\extension\enum\base\BaseEnum;
use UnexpectedValueException;

class JwtHelper {

    public static function forgeBySubject(array $subject, JwtProfileEntity $profileEntity, $keyId = null, $head = null) : JwtEntity {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = $subject;
        $jwtEntity->token = self::sign($jwtEntity, $profileEntity, $keyId, $head);
        return $jwtEntity;
    }

    public static function sign(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity, $keyId = null, $head = null) : string {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $keyId = $keyId ?  : StringHelper::genUuid();
        $token = self::signToken($jwtEntity, $profileEntity, $keyId, $head);
        return $token;
    }

    public static function decode($token, JwtProfileEntity $profileEntity) : JwtEntity {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $jwtEntity = self::decodeToken($token, $profileEntity);
        $jwtEntity->token = $token;
        return $jwtEntity;
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

    private static function decodeToken(string $token, JwtProfileEntity $profileEntity) : JwtEntity {
        // todo: make select key (public or private)
        $key = $profileEntity->key->private;
        $decoded = JWT::decode($token, $key, $profileEntity->allowed_algs);
        $jwtEntity = new JwtEntity((array)$decoded);
        return $jwtEntity;
    }

    private static function tokenDecode(string $jwt) : JwtTokenEntity {
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

    private static function signToken(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity, string $keyId = null, $head = null) : string {
        if($profileEntity->audience) {
            $jwtEntity->audience = ArrayHelper::merge($jwtEntity->audience, $profileEntity->audience);
        }
        if(!$jwtEntity->expire_at && $profileEntity->life_time) {
            $jwtEntity->expire_at = TIMESTAMP + $profileEntity->life_time;
        }
        $data = self::entityToToken($jwtEntity);
        return JWT::encode($data, $profileEntity->key->private, $profileEntity->default_alg, $keyId, $head);
    }

    private static function entityToToken(JwtEntity $jwtEntity) {
        $data = $jwtEntity->toArray();
        $data = array_filter($data, function ($value) {return $value !== null;});
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
