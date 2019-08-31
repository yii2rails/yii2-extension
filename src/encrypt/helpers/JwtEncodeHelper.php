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
use yii2rails\extension\jwt\entities\TokenEntity;

class JwtEncodeHelper
{

    public static function decode(string $jwt, JwtProfileEntity $profileEntity): TokenDto
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
        $tokenDto->signature_encoded = Base64Helper::urlSafeEncode($signature);
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
        if (!static::verify($tokenDto, $key)) {
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
        switch ($function) {
            case EncryptFunctionEnum::HASH_HMAC:
                return hash_hmac($algorithm, $msg, $key, true);
            case EncryptFunctionEnum::OPENSSL:
                $signature = '';
                $success = openssl_sign($msg, $signature, $key, $algorithm);
                if (!$success) {
                    throw new DomainException("OpenSSL unable to sign data");
                } else {
                    return $signature;
                }
        }
    }

    private static function verify(TokenDto $tokenDto, string $key)
    {
        JwtModelHelper::validateKey($tokenDto, $key);
        $msg = self::buildTokenFromDto($tokenDto, false);
        $signature = $tokenDto->signature;
        $alg = $tokenDto->header->alg;

        list($function, $algorithm) = JwtAlgorithmEnum::$supportedAlgorithms[$alg];
        switch ($function) {
            case EncryptFunctionEnum::OPENSSL:
                $success = openssl_verify($msg, $signature, $key, $algorithm);
                if ($success === 1) {
                    return true;
                } elseif ($success === 0) {
                    return false;
                }
                // returns 1 on success, 0 on failure, -1 on error.
                throw new DomainException(
                    'OpenSSL error: ' . openssl_error_string()
                );
            case EncryptFunctionEnum::HASH_HMAC:
            default:
                $hash = hash_hmac($algorithm, $msg, $key, true);
                if (function_exists('hash_equals')) {
                    return hash_equals($signature, $hash);
                }
                $len = min(EncryptHelper::safeStrlen($signature), EncryptHelper::safeStrlen($hash));

                $status = 0;
                for ($i = 0; $i < $len; $i++) {
                    $status |= (ord($signature[$i]) ^ ord($hash[$i]));
                }
                $status |= (EncryptHelper::safeStrlen($signature) ^ EncryptHelper::safeStrlen($hash));

                return ($status === 0);
        }
    }

}
