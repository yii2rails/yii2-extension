<?php

namespace tests\functional\encrypt\helpers;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use yii\helpers\ArrayHelper;
use yii2rails\extension\encrypt\entities\JwtEntity;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\helpers\JwtHelper;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\Unit;

class JwtHelperTest extends Unit {
	
	const PACKAGE = 'yii2rails/yii2-extension';

	private $profile = [
        'key' => [
            'private' => 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz',
        ],
    ];
    private $profileRsa = [
        'key' => [
            'type' => OPENSSL_KEYTYPE_RSA,
            'private' => '-----BEGIN PRIVATE KEY-----
MIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEAzC7Yuot/UR4sODkS
...
l5UIxll1OJQ4ChvGjMpfUWHIAcVVAiEA0KqXpZZiNCNFHxU9RrYeIXmiFfh4PRc6
AlnzJRz8c5M=
-----END PRIVATE KEY-----',
            'public' => '-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAMwu2LqLf1EeLDg5Ek573pYKTX473lHy...
-----END PUBLIC KEY-----',
        ],
        'algorithm' => EncryptAlgorithmEnum::SHA256,
    ];
	private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjdhNzI0MDM2LWFjMzktNGE2Yi1kODhhLWU3MzE4MmQzZGQ2YyJ9.eyJzdWJqZWN0IjoxMjMsImF1ZGllbmNlIjpbXSwiZXhwaXJlX2F0IjoxODgyMzQyMzQ3fQ.1uXnpzNY2b6YNRWcY5Wl83jvEE-v_qW-oYCbfGv1iWg';
    private $tokenExpired = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6ImM0YTNmNTViLThjYWQtNDYwZC04YjViLTYyMjU1YWY2ZWEyYiJ9.eyJzdWJqZWN0IjoxMjMsImF1ZGllbmNlIjpbXSwiZXhwaXJlX2F0IjoxNTY3MDA2NTExfQ.MXhmeeSWgNJMII7_tvlhk4deBsvV7nHAxTU1qNvBmLg';

    public function testSign() {
        $profileEntity = new JwtProfileEntity($this->profile);
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = 123;
        $token = JwtHelper::sign($jwtEntity, $profileEntity);
        $jwtEntityDecoded = JwtHelper::decode($token, $profileEntity);
        $this->tester->assertEquals($jwtEntity->subject, $jwtEntityDecoded->subject);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testDecodeExpired() {
        $profileEntity = new JwtProfileEntity($this->profile);

        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = 123;
        $jwtEntity->expire_at = TIMESTAMP - TimeEnum::SECOND_PER_HOUR;
        $token = JwtHelper::sign($jwtEntity, $profileEntity);

        try {
            $jwtEntityDecoded = JwtHelper::decode($token, $profileEntity);
            $this->tester->assertBad($jwtEntityDecoded);
        } catch (ExpiredException $e) {
            $this->tester->assertExceptionMessage('Expired token', $e);
        }
    }

    public function testBegin()
    {
        $profileEntity = new JwtProfileEntity($this->profile);
        $userId = 1;
        $jwtEntity = $this->forgeTokenEntity($userId);
        $jwtEntity->begin_at = TIMESTAMP + TimeEnum::SECOND_PER_HOUR;
        $jwtEntity->token = JwtHelper::sign($jwtEntity, $profileEntity, '6c6979ec-9575-4794-9303-0d2b851edb02');
        try {
            $jwtEntityDecoded = JwtHelper::decode($jwtEntity->token, $profileEntity);
            $this->tester->assertBad($jwtEntityDecoded);
        } catch (BeforeValidException $e) {
            $this->tester->assertExceptionMessageRegexp('#Cannot handle token prior to#', $e);
        }
    }

    public function testDecode() {
        $profileEntity = new JwtProfileEntity($this->profile);
        $jwtEntityDecoded = JwtHelper::decode($this->token, $profileEntity);
        $this->tester->assertEquals(123, $jwtEntityDecoded->subject);
        $this->tester->assertEquals($jwtEntityDecoded->token, $this->token);
        //$this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->subject->id);
    }

    public function testDecodeFail() {
        $profileEntity = new JwtProfileEntity($this->profile);
        $failToken = $this->token . '1';
        try {
            JwtHelper::decode($failToken, $profileEntity);
            $this->tester->assertTrue(false);
        } catch (SignatureInvalidException $e) {
            $this->tester->assertTrue(true);
        }
    }

    public function testSignAndDecodeByRsa()
    {
        $profileEntity = new JwtProfileEntity($this->profileRsa);
        $userId = 1;
        $jwtEntity = $this->forgeTokenEntity($userId);
        $jwtEntity->token = JwtHelper::sign($jwtEntity, $profileEntity);
        $jwtEntityDecoded = JwtHelper::decode($jwtEntity->token, $profileEntity);
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->subject->id);
    }

    public function testSignAndDecodeRaw()
    {
        $profileEntity = new JwtProfileEntity($this->profile);
        $userId = 1;
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjZjNjk3OWVjLTk1NzUtNDc5NC05MzAzLTBkMmI4NTFlZGIwMiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkuZXhhbXBsZS5jb21cL3YxXC9hdXRoIiwic3ViamVjdCI6eyJpZCI6MX0sInN1YiI6Imh0dHA6XC9cL2FwaS5leGFtcGxlLmNvbVwvdjFcL3VzZXJcLzEiLCJhdWQiOlsiaHR0cDpcL1wvYXBpLmNvcmUueWlpIl0sImV4cCI6MTUzNjI0NzQ2Nn0.XjAxVetPxtldVYLQwkVmKNwbjlatLD5yo_PXfHcwEHo';
        $decoded = JwtHelper::decodeRaw($token, $profileEntity);
        $this->tester->assertRegExp('#[\w]{8}-[\w]{4}-[\w]{4}-[\w]{4}-[\w]{12}#', $decoded->header->kid);
        $this->tester->assertNotEmpty($decoded->sig);
        $this->tester->assertArraySubset([
            'sig' => base64_decode('XjAxVetPxtldVYLQwkVmKNwbjlatLD5yo/PXfHcwEHo='),
            'header' => [
                'typ' => 'JWT',
                'alg' => 'HS256',
                'kid' => '6c6979ec-9575-4794-9303-0d2b851edb02',
            ],
            'payload' => [
                'iss' => 'http://api.example.com/v1/auth',
                'sub' => 'http://api.example.com/v1/user/1',
                'aud' => [
                    'http://api.core.yii',
                ],
                'exp' => 1536247466,
                'subject' => [
                    'id' => $userId,
                ],
            ],
        ], ArrayHelper::toArray($decoded));
    }

    public function testForge()
    {
        $profileEntity = new JwtProfileEntity($this->profile);
        $subject = [
            'id' => 1,
        ];
        $jwtEntity = JwtHelper::forgeBySubject($subject, $profileEntity, '6c6979ec-9575-4794-9303-0d2b851edb02');
        $jwtEntityDecoded = JwtHelper::decode($jwtEntity->token, $profileEntity);
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->subject->id);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $jwtEntity->token);
    }

    private function forgeTokenEntity($userId) {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => $userId,
        ];
        return $jwtEntity;
    }

}
