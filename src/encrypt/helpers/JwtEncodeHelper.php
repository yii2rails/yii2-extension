<?php

namespace yii2rails\extension\encrypt\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\extension\encrypt\dto\TokenDto;
use yii2rails\extension\encrypt\entities\JwtHeaderEntity;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\entities\KeyEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\EncryptDirectionEnum;
use yii2rails\extension\encrypt\enums\EncryptFunctionEnum;
use yii2rails\extension\encrypt\enums\EncryptKeyTypeEnum;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\encrypt\exceptions\BeforeValidException;
use yii2rails\extension\encrypt\exceptions\ExpiredException;
use yii2rails\extension\encrypt\exceptions\SignatureInvalidException;
use DomainException;
use UnexpectedValueException;
use InvalidArgumentException;
use DateTime;
use yii2rails\extension\encrypt\strategies\func\FuncContext;
use yii2rails\extension\jwt\entities\ProfileEntity;
use yii2rails\extension\jwt\entities\TokenEntity;

class JwtEncodeHelper
{

    public static function decode(string $jwt): TokenDto
    {
        $tokenDto = JwtModelHelper::parseToken($jwt);
        return $tokenDto;
    }

    public static function verifyTokenDto(TokenDto $tokenDto, JwtProfileEntity $profileEntity)
    {
        JwtModelHelper::validateToken($tokenDto, $profileEntity->allowed_algs);
        JwtModelHelper::verifyTime($tokenDto);


        self::verifySignature($tokenDto, $profileEntity->key);
    }



    public static function encode(array $payload, KeyEntity $keyEntity, JwtHeaderEntity $jwtHeaderEntity = null) : string
    {
        $tokenDto = new TokenDto;
        $tokenDto->header_encoded = JwtSegmentHelper::encodeSegment(ArrayHelper::toArray($jwtHeaderEntity));
        $tokenDto->payload_encoded = JwtSegmentHelper::encodeSegment($payload);
        $signature = static::sign($tokenDto, $keyEntity, $jwtHeaderEntity->alg);
        $tokenDto->signature_encoded = SafeBase64Helper::encode($signature);
        return self::buildTokenFromDto($tokenDto);
    }

    private static function buildTokenFromDto(TokenDto $tokenDto, $full = true) : string
    {
        $token = $tokenDto->header_encoded . '.' . $tokenDto->payload_encoded;
        if ($full && $tokenDto->signature_encoded) {
            $token .= '.' . $tokenDto->signature_encoded;
        }
        return $token;
    }

    private static function extractKey(KeyEntity $keyEntity, $type = EncryptKeyTypeEnum::PRIVATE) {

        $isRsa = $keyEntity->type === OPENSSL_KEYTYPE_RSA;
        if($isRsa) {
            $key = ArrayHelper::getValue($keyEntity, $type);
        } else {
            $key = ArrayHelper::getValue($keyEntity, EncryptKeyTypeEnum::PRIVATE);
        }

        /*if($keyEntity->type === OPENSSL_KEYTYPE_RSA && ) {
            $key = $keyEntity->public;
        } else {
            $key = $keyEntity->private;
        }*/
        if (empty($key)) {
            throw new InvalidArgumentException('Key may not be empty');
        }
        return $key;
    }

    private static function verifySignature(TokenDto $tokenDto, KeyEntity $keyEntity)
    {
        $isVerified = static::verify($tokenDto, $keyEntity);
        if (!$isVerified) {
            throw new SignatureInvalidException('Signature verification failed');
        }
    }

    private static function sign(TokenDto $tokenDto, KeyEntity $keyEntity, $alg = JwtAlgorithmEnum::HS256)
    {
        $key = self::extractKey($keyEntity, EncryptKeyTypeEnum::PRIVATE);
        $msg = self::buildTokenFromDto($tokenDto, false);
        if (!JwtAlgorithmEnum::isSupported($alg)) {
            throw new DomainException('Algorithm not supported');
        }
        $algorithm = JwtAlgorithmEnum::getHashAlgorithm($alg);
        $function = self::getFunction($keyEntity->type);
        $loginContext = new FuncContext;
        $loginContext->setStrategyName($function);
        return $loginContext->sign($msg, $algorithm, $key);
    }

    private static function verify(TokenDto $tokenDto, KeyEntity $keyEntity)
    {
        $key = self::extractKey($keyEntity, EncryptKeyTypeEnum::PUBLIC);
        JwtModelHelper::validateKey($tokenDto, $key);
        $msg = self::buildTokenFromDto($tokenDto, false);
        $signature = $tokenDto->signature;
        $alg = $tokenDto->header->alg;
        $algorithm = JwtAlgorithmEnum::getHashAlgorithm($alg);
        $function = self::getFunction($keyEntity->type);
        $loginContext = new FuncContext;
        $loginContext->setStrategyName($function);
        return $loginContext->verify($msg, $algorithm, $key, $signature);
    }

    private static function getFunction($type)
    {
        $isRsa = $type === OPENSSL_KEYTYPE_RSA;
        $function = $isRsa ? EncryptFunctionEnum::OPENSSL : EncryptFunctionEnum::HASH_HMAC;
        return $function;
    }

}
