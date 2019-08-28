<?php

namespace tests\functional\encrypt\helpers;

use Firebase\JWT\SignatureInvalidException;
use yii2rails\extension\encrypt\entities\JwtProfileEntity;
use yii2rails\extension\encrypt\helpers\JwtHelper;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\jwt\entities\TokenEntity;
use yii2tool\test\Test\Unit;

class JwtHelperTest extends Unit {
	
	const PACKAGE = 'yii2rails/yii2-extension';

	private $profile = [
        'key' => [
            'private' => 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz',
        ],
    ];
	private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjdhNzI0MDM2LWFjMzktNGE2Yi1kODhhLWU3MzE4MmQzZGQ2YyJ9.eyJzdWJqZWN0IjoxMjMsImF1ZGllbmNlIjpbXSwiZXhwaXJlX2F0IjoxODgyMzQyMzQ3fQ.1uXnpzNY2b6YNRWcY5Wl83jvEE-v_qW-oYCbfGv1iWg';
    private $tokenExpired = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IjYyNGFkM2ZlLTJlNzgtNDVkOC1mMTZkLTQ1Mjk1MzFjYzU1ZiJ9.eyJzdWJqZWN0IjoxMjMsImF1ZGllbmNlIjpbXSwiZXhwaXJlX2F0IjoxNTY2OTg1NDEyfQ.GMKPoQfjt3dctM4EA2YcLzxWTgbB_8Ho8I0NE2V7aK0';

	public function testSign() {
        $profileEntity = new JwtProfileEntity($this->profile);
        $tokenEntity = new TokenEntity;
        $tokenEntity->subject = 123;
        $token = JwtHelper::sign($tokenEntity, $profileEntity);
        JwtHelper::decode($token, $profileEntity);
	}

    public function testDecode() {
        $profileEntity = new JwtProfileEntity($this->profile);
        $tokenEntity = JwtHelper::decode($this->token, $profileEntity);
        $this->tester->assertEquals($tokenEntity->token, $this->token);
    }

    public function testDecodeRaw() {
        $profileEntity = new JwtProfileEntity($this->profile);
        $tokenData = JwtHelper::decodeRaw($this->token, $profileEntity);
        //d($tokenData);
        //$this->tester->assertEquals('', json_encode($tokenData));
    }

    /*public function testDecodeExpired() {
        $profileEntity = new JwtProfileEntity($this->profile);
        $failToken = $this->token;
        $decoded = JwtHelper::decode($failToken, $profileEntity);
    }*/

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

}
