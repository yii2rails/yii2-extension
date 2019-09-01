<?php

namespace yii2rails\extension\encrypt\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\extension\encrypt\dto\TokenDto;
use yii2rails\extension\encrypt\entities\JwtHeaderEntity;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\EncryptFunctionEnum;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\encrypt\exceptions\BeforeValidException;
use yii2rails\extension\encrypt\exceptions\ExpiredException;
use yii2rails\extension\encrypt\exceptions\SignatureInvalidException;
use DomainException;
use UnexpectedValueException;
use InvalidArgumentException;
use DateTime;
use yii2rails\extension\encrypt\strategies\func\FuncContext;
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
        $key = self::extractKey($profileEntity);
        self::verifySignature($tokenDto, $key);
    }

    public static function encode(array $payload, string $key, JwtHeaderEntity $jwtHeaderEntity = null) : string
    {
        $tokenDto = new TokenDto;
        $tokenDto->header_encoded = JwtSegmentHelper::encodeSegment(ArrayHelper::toArray($jwtHeaderEntity));
        $tokenDto->payload_encoded = JwtSegmentHelper::encodeSegment($payload);
        $signature = static::sign($tokenDto, $key, $jwtHeaderEntity->alg);
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

    private static function extractKey(JwtProfileEntity $profileEntity) {
        $key = $profileEntity->key->private;
        if (empty($key)) {
            throw new InvalidArgumentException('Key may not be empty');
        }
        return $key;
    }

    private static function verifySignature(TokenDto $tokenDto, string $key)
    {
        $isVerified = static::verify($tokenDto, $key);
        if (!$isVerified) {
            throw new SignatureInvalidException('Signature verification failed');
        }
    }

    private static function sign(TokenDto $tokenDto, string $key, $alg = JwtAlgorithmEnum::HS256)
    {
        $msg = self::buildTokenFromDto($tokenDto, false);
        if (!JwtAlgorithmEnum::isSupported($alg)) {
            throw new DomainException('Algorithm not supported');
        }
        list($function, $algorithm) = JwtAlgorithmEnum::$supportedAlgorithms[$alg];
        $loginContext = new FuncContext;
        return $loginContext->sign($function, $msg, $algorithm, $key);
    }

    private static function verify(TokenDto $tokenDto, string $key)
    {
        JwtModelHelper::validateKey($tokenDto, $key);
        $msg = self::buildTokenFromDto($tokenDto, false);
        $signature = $tokenDto->signature;
        $alg = $tokenDto->header->alg;
        list($function, $algorithm) = JwtAlgorithmEnum::$supportedAlgorithms[$alg];
        $loginContext = new FuncContext;
        return $loginContext->verify($function, $msg, $algorithm, $key, $signature);
    }

}
