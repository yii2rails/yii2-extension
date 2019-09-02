<?php

namespace tests\functional\encrypt\helpers;

use yii2rails\extension\encrypt\libs\ProfileContainer;
use yii2rails\extension\psr\container\Container;
use yii2rails\extension\encrypt\enums\JwtAlgorithmEnum;
use yii2rails\extension\encrypt\exceptions\BeforeValidException;
use yii2rails\extension\encrypt\exceptions\ExpiredException;
use yii2rails\extension\encrypt\exceptions\SignatureInvalidException;
use yii\helpers\ArrayHelper;
use yii2rails\extension\encrypt\entities\JwtEntity;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\enums\EncryptAlgorithmEnum;
use yii2rails\extension\encrypt\helpers\JwtHelper;
use yii2rails\extension\encrypt\libs\JwtService;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\Unit;

class JwtServiceTest extends Unit
{

    const PACKAGE = 'yii2rails/yii2-extension';

    private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjdhNzI0MDM2LWFjMzktNGE2Yi1kODhhLWU3MzE4MmQzZGQ2YyJ9.eyJzdWJqZWN0IjoxMjMsImF1ZGllbmNlIjpbXSwiZXhwaXJlX2F0IjoxODgyMzQyMzQ3fQ.1uXnpzNY2b6YNRWcY5Wl83jvEE-v_qW-oYCbfGv1iWg';
    private $tokenExpired = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6ImM0YTNmNTViLThjYWQtNDYwZC04YjViLTYyMjU1YWY2ZWEyYiJ9.eyJzdWJqZWN0IjoxMjMsImF1ZGllbmNlIjpbXSwiZXhwaXJlX2F0IjoxNTY3MDA2NTExfQ.MXhmeeSWgNJMII7_tvlhk4deBsvV7nHAxTU1qNvBmLg';

    private function prepareJwtEntity(int $subjectId = 123): JwtEntity
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = $subjectId;
        return $jwtEntity;
    }

    private function prepareJwtService(): JwtService
    {
        $profiles = [
            'hmac1' => [
                'key' => [
                    'private' => 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz',
                ],
            ],
            'hmac2' => [
                'key' => [
                    'private' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
                ],
            ],
            'rsa1' => [
                'key' => [
                    'type' => OPENSSL_KEYTYPE_RSA,
                    'private_file' => __DIR__ . '/../../../_data/key/rsa1/priv',
                    'public_file' => __DIR__ . '/../../../_data/key/rsa1/pub',
                ],
            ],
            'rsa2' => [
                'key' => [
                    'type' => OPENSSL_KEYTYPE_RSA,
                    'private_file' => __DIR__ . '/../../../_data/key/rsa2/priv',
                    'public_file' => __DIR__ . '/../../../_data/key/rsa2/pub',
                ],
            ],
        ];
        $jwtService = new JwtService($profiles);
        return $jwtService;
    }

    public function testSetProfile1()
    {
        $definitions = [
            'jwt' => [
                'class' => JwtService::class,
                'profiles' => [
                    'hmac1' => [
                        'key' => [
                            'private' => 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz',
                        ],
                    ],
                ],
            ],
        ];
        $container = new Container($definitions);
        $jwtService = $container->jwt;

        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $token = $jwtService->sign($jwtEntity, 'hmac1');
        $jwtEntityDecoded = $jwtService->decode($token, 'hmac1');
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->payload->subject->id);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testSetProfile2()
    {

        $profiles = [
            'hmac1' => [
                'key' => [
                    'private' => 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz',
                ],
            ],
        ];

        $profileContainer = new ProfileContainer($profiles);

        $definitions = [
            'jwt' => [
                'class' => JwtService::class,
                'profiles' => $profileContainer,
            ],
        ];
        $container = new Container($definitions);
        $jwtService = $container->jwt;

        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $token = $jwtService->sign($jwtEntity, 'hmac1');
        $jwtEntityDecoded = $jwtService->decode($token, 'hmac1');
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->payload->subject->id);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

   /* public function testSetProfile()
    {
        $jwtService = new JwtService;
        $jwtService->setProfile('hmac1', [
            'key' => [
                'private' => 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz',
            ],
        ]);
        $jwtService->setProfile('hmac2', [
            'key' => [
                'private' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            ],
        ]);
        $jwtService->setProfile('rsa1', [
            'key' => [
                'type' => OPENSSL_KEYTYPE_RSA,
                'private_file' => __DIR__ . '/../../../_data/key/rsa1/priv',
                'public_file' => __DIR__ . '/../../../_data/key/rsa1/pub',
            ],
        ]);
        $jwtService->setProfile('rsa2', [
            'key' => [
                'type' => OPENSSL_KEYTYPE_RSA,
                'private_file' => __DIR__ . '/../../../_data/key/rsa2/priv',
                'public_file' => __DIR__ . '/../../../_data/key/rsa2/pub',
            ],
        ]);
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService->sign($jwtEntity, 'hmac1');
        $jwtService->sign($jwtEntity, 'hmac2');
        $jwtService->sign($jwtEntity, 'rsa1');
        $jwtService->sign($jwtEntity, 'rsa2');
    }*/

    public function testSign()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'hmac1');
        $jwtEntityDecoded = $jwtService->decode($token, 'hmac1');
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->payload->subject->id);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testVerifyExpired()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtEntity->expire_at = TIMESTAMP - TimeEnum::SECOND_PER_HOUR;
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'hmac1');
        try {
            $jwtEntityDecoded = $jwtService->verify($token, 'hmac1');
            $this->tester->assertBad($jwtEntityDecoded);
        } catch (ExpiredException $e) {
            $this->tester->assertExceptionMessage('Expired token', $e);
        }
    }

    public function testBegin()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtEntity->begin_at = TIMESTAMP + TimeEnum::SECOND_PER_HOUR;
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'hmac1');
        try {
            $jwtEntityDecoded = $jwtService->verify($token, 'hmac1');
            $this->tester->assertBad($jwtEntityDecoded);
        } catch (BeforeValidException $e) {
            $this->tester->assertExceptionMessageRegexp('#Cannot handle token prior to#', $e);
        }
    }

    public function testVerify()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'hmac1');
        $jwtEntityDecoded = $jwtService->verify($token, 'hmac1');
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->subject->id);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testVerofyFail()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'hmac1');
        $failToken = $this->token . '1';
        try {
            $jwtEntityDecoded = $jwtService->verify($failToken, 'hmac1');
            $this->tester->assertTrue(false);
        } catch (SignatureInvalidException $e) {
            $this->tester->assertTrue(true);
        }
    }

    public function testSignAndVerifyByRsa1()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'rsa1');
        $jwtEntityDecoded = $jwtService->verify($token, 'rsa1');
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->subject->id);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testSignAndVerifyByRsa2()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'rsa2');
        $jwtEntityDecoded = $jwtService->verify($token, 'rsa2');
        $this->tester->assertEquals($jwtEntity->subject['id'], $jwtEntityDecoded->subject->id);
        $this->tester->assertRegExp('#^[a-zA-Z0-9-_\.]+$#', $token);
    }

    public function testSignAndVerifyByRsaAlien()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService = $this->prepareJwtService();

        $token = $jwtService->sign($jwtEntity, 'rsa1');
        try {
            $jwtEntityDecoded = $jwtService->verify($token, 'rsa2');
            $this->tester->assertTrue(false);
        } catch (SignatureInvalidException $e) {
            $this->tester->assertTrue(true);
        }

        $token = $jwtService->sign($jwtEntity, 'rsa2');
        try {
            $jwtEntityDecoded = $jwtService->verify($token, 'rsa1');
            $this->tester->assertTrue(false);
        } catch (SignatureInvalidException $e) {
            $this->tester->assertTrue(true);
        }
    }

    public function testSignAndDecodeRaw()
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = [
            'id' => 123,
        ];
        $jwtService = $this->prepareJwtService();
        $token = $jwtService->sign($jwtEntity, 'hmac1');
        $decoded = $jwtService->decode($token, 'hmac1');

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

}
