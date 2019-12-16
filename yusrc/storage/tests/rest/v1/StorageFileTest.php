<?php

namespace yubundle\storage\tests\rest\v1;

use api\tests\functional\v1\appeal\AppealSchema;
use yubundle\storage\tests\rest\v1\StorageSchema;
use yubundle\user\tests\rest\v1\UserSchema;
use yii2tool\test\helpers\CurrentIdTestHelper;
use yii2tool\test\Test\BaseActiveApiTest;
use yii2bundle\account\domain\v3\helpers\test\AuthTestHelper;

class StorageFileTest extends BaseActiveApiTest
{

    public $package = 'api';
    public $point = 'v1';
    public $resource = 'file-storage';

    public function testAll()
    {
        AuthTestHelper::authByLogin('admin');
        $this->readCollection($this->resource, [], StorageSchema::$file, true);
    }

    public function testOne()
    {
        AuthTestHelper::authByLogin('admin');
        $this->readEntity($this->resource, 1, StorageSchema::$file);
    }

    public function testRelation()
    {
        AuthTestHelper::authByLogin('vitaliy');
        $this->assertRelationContract($this->resource, 1, [
            'editor' => UserSchema::$person,
            'ext' => StorageSchema::$extension,
        ]);
    }
}
