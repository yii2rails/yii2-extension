<?php

namespace yubundle\storage\tests\rest\v1;

use yubundle\storage\tests\rest\v1\StorageSchema;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2tool\test\enums\TypeEnum;
use yii2rails\extension\common\helpers\Helper;
use yii2rails\extension\common\helpers\TempHelper;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\web\enums\HttpMethodEnum;

class StoragePersonalTest extends BaseActiveApiTest
{

    public $package = 'api';
    public $point = 'v1';
    public $resource = 'storage-personal';

    public function testUploadFile() {
        AuthTestHelper::authByLogin('vitaliy');

        $requestEntity = new RequestEntity;
        $requestEntity->uri = $this->resource;
        $requestEntity->method = HttpMethodEnum::POST;
        $extId = Helper::microtimeId();
        $fileName = TempHelper::save($extId . '.data', $extId);
        $requestEntity->files = [
            'file' => $fileName,
        ];
        $responseEntity = $this->sendRequest($requestEntity);
        //d($responseEntity);
        $this->tester->assertEquals(201, $responseEntity->status_code);
        $lastId = $this->getLastId($responseEntity);
        $this->tester->assertType('lastId', TypeEnum::INTEGER, $lastId);

        $actual = $this->readEntity($this->resource, $lastId, StorageSchema::$file);

        //$this->tester->assertEquals('0a4368e3', $actual['hash']);
        $this->tester->assertEquals('14', $actual['size']);
        $this->assertRemoteFile($actual['url']['source'], $extId);
        $this->assertRemoteFile($actual['url']['constant'], $extId);
        $this->assertRemoteFile($actual['url']['download'], $extId);
    }

    public function testDeleteImageIfExtist() {
        $collection = $this->readCollection($this->resource, ['hash' => '235a35d8']);
        if($collection) {
            $item = $collection[0];
            $this->deleteEntity($this->resource, $item['id']);
        }
        $collection = $this->readCollection($this->resource, ['hash' => '235a35d8']);
        $this->tester->assertEmpty($collection);
    }

    public function testUploadImage() {
        AuthTestHelper::authByLogin('vitaliy');

        $files = [
            'file' => __DIR__ . '/../../../tests/_data/s1200.jpg',
        ];
        $responseEntity = $this->uploadFile($this->resource, $files);

        $this->tester->assertEquals(201, $responseEntity->status_code);
        $lastId = $this->getLastId($responseEntity);

        $this->tester->assertType('lastId', TypeEnum::INTEGER, $lastId);

        $actual = $this->readEntity($this->resource, $lastId, StorageSchema::$file, ['expand' => 'service.thumbs']);


        $this->assertRemoteFileMd5($actual['url']['source'], '83e8c49971858f667f33b09bab44d8c6');
        $this->assertRemoteFileMd5($actual['url']['constant'], '83e8c49971858f667f33b09bab44d8c6');
        $this->assertRemoteFileMd5($actual['url']['download'], '83e8c49971858f667f33b09bab44d8c6');

        $this->assertRemoteFileMd5($actual['thumbUrls']['128x128'], '5a9c46b8b994988d10d84fdc82f800d8');
        $this->assertRemoteFileMd5($actual['thumbUrls']['256x256'], '9c67152d0d1b73bad89996583989ebf8');
        $this->assertRemoteFileMd5($actual['thumbUrls']['512x512'], '83cb679401b3de6b0b05c182bd2195a8');
    }

}
