<?php

namespace tests\functional\encrypt\helpers;

use yii2rails\domain\data\Query;
use yii2rails\domain\helpers\DomainHelper;
use yii2rails\extension\encrypt\entities\KeyEntity;
use yii2rails\extension\encrypt\enums\EncryptFunctionEnum;
use yii2rails\extension\encrypt\helpers\Base64Helper;
use yii2rails\extension\encrypt\helpers\RsaHelper;
use yii2rails\extension\encrypt\helpers\SignatureHelper;
use yii2rails\extension\package\domain\entities\ConfigEntity;
use yii2rails\extension\package\domain\entities\GroupEntity;
use yii2rails\extension\package\domain\entities\PackageEntity;
use yii2tool\test\Test\Unit;

class SignatureHelperTest extends Unit {
	
	const PACKAGE = 'yii2rails/yii2-extension';
    const PRIVATE_KEY = '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDPfin8XzQdAxjm
rKtnQbvB3UFVGAKAnGNFZNNEOSsnh+Nnm0YHWFOJmL2qockHNX/E8/3Md7ZO0n7e
DWEKDQPD2XA7UNv28jV6utEgeqaD+ZmaYDXwnlXN4UxRUAM9GkkWyVdphKphvt0j
oayCVKLwlL8GIPvBjtnU5lQhQBdEgKGb4cqHEbcRLq7LtHZQkVuvIWXEfa3045iE
Ge/RYeUWqyOJqE29ed1DXjQ9z6ON007n6CrizTzFTWz1t9L1p7LuPK2IJFU9ITJL
5kCkoT6/kYY5G50waCbAVR0Y5HRUblBOgf/TtFb/iYvnzpmqjQySVE1DGFrWqjGT
Aa3jwHEzAgMBAAECggEBAL180EcDgooELBdtFNMlepwevO1DEfX128qhuzYQp1Jg
jNIvQRKwHQoJjRxoxzdoKhjpYZv6r2joXqfWvAn7vLZ8ujvRKjApHaHJPfZVTAA2
T4pl1py5XE66M4IGeg6xFJJUqRlZ6Gd/cGB74xjaxjrC/HhSYtdu54vAFJCRAS6y
TO+BFjlfvV7FxpdVvVt/5X7j0bt+e0BBFWzCBsiMpyqpEvYG0BnFYvm3C7Cz9Zm5
9n2v6wVT6czfnWOBmqjcQqFlYFPEmwzmq+QvB0sVmuU5E9hll4SCpUG6DtWh8ZJ3
pCSqDYqsM1CrdAo4HwqimdK+L3xKrokB6GyWyfFtufECgYEA5sbhoLEi8suVCnYw
RGaHppOQZwRbf6VMyXFOCRAKWvw8XKMZadmZjXIWOhzhYgirPY5UuYZLnjvr26IR
qLyYuudKXD/KMcD8+EQsQzam7Kiht3fTUnAMmvtmhp8iUiCC2/27U/elrWKyq/R0
8Iw2Fx+i0zyINIUQRDO+CwMMzqUCgYEA5ivL9rO/iGu9omVbumqk64eJ1zjbWDnT
D6dXzqd1MPutbu+v4GxIjnEgQpIyWaeDnpTOnN+28dBAPMRhAgOCswSGiauiWqOq
Xq751gjdoNLd8NkKj76pvuQDo3i4Sl+/uoOmb66+eHzbyD0BdMB4Y3+t5lodxzEM
ddOLF29T0PcCgYBGy9Jh4pWPGGdDi1hpU27f6jsNb2fGc+ZnkevXmeUjXmgCH0Ln
lwqlOs+Yyk1WLsShoK1bMNi31TgY69IxCpJsHBJS1Hrp4oN4gil2ASkaRu09hHP+
wUMMtH0SZXU47qJWbLNwIfPgifu4BsltFgZt95WS6en6+qsv0RPG+wuNxQKBgQC3
ZModBVdk4UO1s0EFJZfGOrZvj9PlVh1/aXyjIfZUUxP7QmtGS8D8DdMAd/A6Uyii
IDsH75Ca1613PZl1u0pWTRLNer3frmw/CPfzvBiq3ZkNIEI0ClzBxnfHtpZQXD4t
5FumvPrykbmksEcKuZiy3ra2xbLYVZJyB13aDFAiPQKBgHCVNIM6uTaPbjpeatfa
8OTVhbIIai35yZtHvmAQ5ND5vB3k5Wm2Af6mGhWdbH7xgpC4FFK8CmRcOcogNffA
V82mXVeT39P+CEnHp8gCJokhwWNOI0WvmX5DnY2BvLDkzuKlhJ3t4+UeU7PhPiJw
m/QcJLWxrgIn1hSCOYDzrvsx
-----END PRIVATE KEY-----';
    const PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAz34p/F80HQMY5qyrZ0G7
wd1BVRgCgJxjRWTTRDkrJ4fjZ5tGB1hTiZi9qqHJBzV/xPP9zHe2TtJ+3g1hCg0D
w9lwO1Db9vI1errRIHqmg/mZmmA18J5VzeFMUVADPRpJFslXaYSqYb7dI6GsglSi
8JS/BiD7wY7Z1OZUIUAXRIChm+HKhxG3ES6uy7R2UJFbryFlxH2t9OOYhBnv0WHl
FqsjiahNvXndQ140Pc+jjdNO5+gq4s08xU1s9bfS9aey7jytiCRVPSEyS+ZApKE+
v5GGORudMGgmwFUdGOR0VG5QToH/07RW/4mL586Zqo0MklRNQxha1qoxkwGt48Bx
MwIDAQAB
-----END PUBLIC KEY-----';

	public function testOpenSsl() {
        $msg = 'Test message';
        $keyEntity = new KeyEntity([
            'private' => self::PRIVATE_KEY,
            'public' => self::PUBLIC_KEY,
        ]);

        $signature = SignatureHelper::sign($msg, $keyEntity->private, EncryptFunctionEnum::OPENSSL);
        $isVerified = SignatureHelper::verify($msg, $keyEntity->public, $signature, EncryptFunctionEnum::OPENSSL);

        $this->tester->assertNotEmpty($signature);
        $this->tester->assertTrue($isVerified);
        $expectedSignature = 'e5No0L5R3rEPeWJ+C0KAxM9EUP2QvU2zua42444jKcrJRfkcNfcm+0JCrtFEevXPbOQNHAJI8a3OMtKBX22zaN9FRrwp5Jmgr+MCsPVs5eqrH0Ys/AvIViyvAfI7yy5SkfvLR0Ff+8LA/UVnQVpMIwJ8A4MvpOTK6NuX8r4b6nRmCoA3HIboxIGh5okcd9MNGil++QZsfVddJxGl3mDkgfMzQjRwc8tpR9kCiUHcJq9MHgV15UQ6VeTMRpBU+QsmLv1xFsT5U5UCg534Ong57CuqKxFAJlGQqhaBG+auiQVWgQR9yHIUfYNlMVYv98CHTV/twz43E1Nb4ofG0Wu1tA==';
        $this->tester->assertEquals($expectedSignature, base64_encode($signature));
	}
	
	public function testHmac() {
        $msg = 'Test message';
        $key = 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz';

        $signature = SignatureHelper::sign($msg, $key);
        $isVerified = SignatureHelper::verify($msg, $key, $signature);

        $this->tester->assertNotEmpty($signature);
        $this->tester->assertTrue($isVerified);
        $this->tester->assertEquals('PO3iX07a6QW1MoZuFKXrLMRM/kESLM42RWWaVJ3ST2s=', base64_encode($signature));
	}

}
