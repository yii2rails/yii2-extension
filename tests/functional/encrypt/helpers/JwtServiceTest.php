<?php

namespace tests\functional\encrypt\helpers;

use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\encrypt\exceptions\BeforeValidException;
use yii2rails\extension\encrypt\exceptions\ExpiredException;
use yii2rails\extension\encrypt\exceptions\SignatureInvalidException;
use yii\helpers\ArrayHelper;
use yii2rails\extension\encrypt\entities\JwtEntity;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\helpers\JwtHelper;
use yii2rails\extension\encrypt\helpers\JwtService;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\Unit;

class JwtServiceTest extends Unit
{

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
TnvelgpNfjveUfIUWGcvNW/kpqL3NG4dZ2wQZ5lhuFid/nZFGdObtdkSbA8toWV8
k0G92wIDAQABAkAYqZ/sCGV8etSEhgA8EqI0JVJu6PRVmZPziaMeJUHNDrK8XuDo
bisPU5RtaHQ/4c+zHBhJCUsXteZzoTEdUeGhAiEA98XzM8iOujJNKFmiibT1B+G/
ecdfcYIJIjMnmZ7nZ38CIQDS9mIp7xi6irfbrsFov0iX/69hzEYCrTFwaPMX6QB3
pQIgZ926at3LPzCw+ZZBtbp+8VPoIZO7ZejeDVEma5aaaN8CIGNXTGBsy9tD6VJU
l5UIxll1OJQ4ChvGjMpfUWHIAcVVAiEA0KqXpZZiNCNFHxU9RrYeIXmiFfh4PRc6
AlnzJRz8c5M=
-----END PRIVATE KEY-----',
                'public' => '-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAMwu2LqLf1EeLDg5Ek573pYKTX473lHy
FFhnLzVv5Kai9zRuHWdsEGeZYbhYnf52RRnTm7XZEmwPLaFlfJNBvdsCAwEAAQ==
-----END PUBLIC KEY-----',
        ],
        'algorithm' => EncryptAlgorithmEnum::SHA256,
    ];
    private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjdhNzI0MDM2LWFjMzktNGE2Yi1kODhhLWU3MzE4MmQzZGQ2YyJ9.eyJzdWJqZWN0IjoxMjMsImF1ZGllbmNlIjpbXSwiZXhwaXJlX2F0IjoxODgyMzQyMzQ3fQ.1uXnpzNY2b6YNRWcY5Wl83jvEE-v_qW-oYCbfGv1iWg';
    private $tokenExpired = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6ImM0YTNmNTViLThjYWQtNDYwZC04YjViLTYyMjU1YWY2ZWEyYiJ9.eyJzdWJqZWN0IjoxMjMsImF1ZGllbmNlIjpbXSwiZXhwaXJlX2F0IjoxNTY3MDA2NTExfQ.MXhmeeSWgNJMII7_tvlhk4deBsvV7nHAxTU1qNvBmLg';

    private function prepareJwtEntity(int $subjectId = 123): JwtEntity
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = $subjectId;
        return $jwtEntity;
    }

    private function prepareJwtService($profileName = 'profile'): JwtService
    {
        $jwtService = new JwtService;
        $profile = $this->{$profileName};
        $jwtService->setProfile('auth1', $profile, JwtProfileEntity::class);
        return $jwtService;
    }

    public function testSign()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = 123;
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'auth1');
        $jwtEntityDecoded = $jwtService->decode($token, 'auth1');
        $this->tester->assertEquals($jwtEntity->subject, $jwtEntityDecoded->payload->subject);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testDecodeExpired()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = 123;
        $jwtEntity->expire_at = TIMESTAMP - TimeEnum::SECOND_PER_HOUR;
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'auth1');
        try {
            $jwtEntityDecoded = $jwtService->verify($token, 'auth1');
            $this->tester->assertBad($jwtEntityDecoded);
        } catch (ExpiredException $e) {
            $this->tester->assertExceptionMessage('Expired token', $e);
        }
    }


    public function testBegin()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = 123;
        $jwtEntity->begin_at = TIMESTAMP + TimeEnum::SECOND_PER_HOUR;
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'auth1');
        try {
            $jwtEntityDecoded = $jwtService->verify($token, 'auth1');
            $this->tester->assertBad($jwtEntityDecoded);
        } catch (BeforeValidException $e) {
            $this->tester->assertExceptionMessageRegexp('#Cannot handle token prior to#', $e);
        }
    }

    public function testVerify()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = 123;
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'auth1');
        $jwtEntityDecoded = $jwtService->verify($token, 'auth1');
        $this->tester->assertEquals($jwtEntity->subject, $jwtEntityDecoded->subject);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testVerofyFail()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = 123;
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'auth1');
        $failToken = $this->token . '1';
        try {
            $jwtEntityDecoded = $jwtService->verify($failToken, 'auth1');
            $this->tester->assertTrue(false);
        } catch (SignatureInvalidException $e) {
            $this->tester->assertTrue(true);
        }
    }

    public function testSignAndVerifyByRsa()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = 123;
        $jwtService = $this->prepareJwtService('profileRsa');
        $token = $jwtService->sign($jwtEntity, 'auth1');
        $jwtEntityDecoded = $jwtService->verify($token, 'auth1');
        $this->tester->assertEquals($jwtEntity->subject, $jwtEntityDecoded->subject);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testSignAndDecodeRaw()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'auth1');
        $decoded = $jwtService->decode($token, 'auth1');

        $this->tester->assertNotEmpty($decoded->signature);
        $this->tester->assertNotEmpty($decoded->payload->exp);
        $this->tester->assertNotEmpty($decoded->header->kid);
        $this->tester->assertArraySubset([
            'header' => [
                'typ' => 'JWT',
                'alg' => 'HS256',
                //'kid' => '8c59ac54-da45-483f-bd',
            ],
            'payload' => [
                'subject' => [
                    'id' => 123,
                ],
                'aud' => [],
                //'exp' => TIMESTAMP,
            ],
            //'signature' => '',
        ], ArrayHelper::toArray($decoded));
    }

    /*public function testForge()
    {
        $profileEntity = new JwtProfileEntity($this->profile);
        $subject = [
            'id' => 1,
        ];
        $jwtEntity = JwtHelper::forgeBySubject($subject, $profileEntity, '6c6979ec-9575-4794-9303-0d2b851edb02');
        $jwtEntityDecoded = JwtHelper::decode($jwtEntity->token, $profileEntity);
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->subject->id);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $jwtEntity->token);
    }*/

    private function forgeTokenEntity($userId)
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => $userId,
        ];
        return $jwtEntity;
    }

}
