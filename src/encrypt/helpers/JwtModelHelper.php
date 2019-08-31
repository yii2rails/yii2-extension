<?php

namespace yii2rails\extension\encrypt\helpers;

use yii\helpers\ArrayHelper;
use yii2rails\extension\encrypt\dto\TokenDto;
use yii2rails\extension\encrypt\entities\JwtHeaderEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\enums\EncryptFunctionEnum;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\encrypt\exceptions\BeforeValidException;
use yii2rails\extension\encrypt\exceptions\ExpiredException;
use yii2rails\extension\encrypt\exceptions\SignatureInvalidException;
use DomainException;
use UnexpectedValueException;
use DateTime;

class JwtModelHelper
{

    /**
     * When checking nbf, iat or expiration times,
     * we want to provide some extra leeway time to
     * account for clock skew.
     */
    public static $leeway = 0;

    public static function parseToken(string $jwt) : TokenDto {
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            throw new UnexpectedValueException('Wrong number of segments');
        }

        $tokenDto = new TokenDto;
        list($tokenDto->header_encoded, $tokenDto->payload_encoded, $tokenDto->signature_encoded) = $tks;
        if (null === ($tokenDto->header = JwtSegmentHelper::decodeSegment($tokenDto->header_encoded))) {
            throw new UnexpectedValueException('Invalid header encoding');
        }
        if (null === $tokenDto->payload = JwtSegmentHelper::decodeSegment($tokenDto->payload_encoded)) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }
        if (false === ($tokenDto->signature = Base64Helper::urlSafeDecode($tokenDto->signature_encoded))) {
            throw new UnexpectedValueException('Invalid signature encoding');
        }
        return $tokenDto;
    }

    public static function verifyTime(TokenDto $tokenDto) {
        $timestamp = time();

        // Check if the nbf if it is defined. This is the time that the
        // token can actually be used. If it's not yet that time, abort.
        if (isset($tokenDto->payload->nbf) && $tokenDto->payload->nbf > ($timestamp + static::$leeway)) {
            throw new BeforeValidException(
                'Cannot handle token prior to ' . date(DateTime::ISO8601, $tokenDto->payload->nbf)
            );
        }

        // Check that this token has been created before 'now'. This prevents
        // using tokens that have been created for later use (and haven't
        // correctly used the nbf claim).
        if (isset($tokenDto->payload->iat) && $tokenDto->payload->iat > ($timestamp + static::$leeway)) {
            throw new BeforeValidException(
                'Cannot handle token prior to ' . date(DateTime::ISO8601, $tokenDto->payload->iat)
            );
        }

        // Check if this token has expired.
        if (isset($tokenDto->payload->exp) && ($timestamp - static::$leeway) >= $tokenDto->payload->exp) {
            throw new ExpiredException('Expired token');
        }
    }

    public static function validateToken(TokenDto $tokenDto, string $key, array $allowed_algs) {
        $alg = $tokenDto->header->alg;
        if (empty($alg)) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        if (!JwtAlgorithmEnum::isSupported($alg)) {
            throw new DomainException('Algorithm not supported');
        }
        if (!JwtAlgorithmEnum::isSupported($alg)) {
            throw new UnexpectedValueException('Algorithm not supported');
        }
        if (!in_array($alg, $allowed_algs)) {
            throw new UnexpectedValueException('Algorithm not allowed');
        }
        if (is_array($key) || $key instanceof \ArrayAccess) {
            if (isset($tokenDto->header->kid)) {
                if (!isset($key[$tokenDto->header->kid])) {
                    throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
                }
                $key = $key[$tokenDto->header->kid];
            } else {
                throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
            }
        }
    }

}
