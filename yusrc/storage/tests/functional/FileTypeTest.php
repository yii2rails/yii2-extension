<?php

namespace tests\functional;

use yii2tool\test\Test\BaseActiveDomainTest;

class FileTypeTest extends BaseActiveDomainTest
{

    public $package = 'vendor/yii2rails/yii2-extension/yusrc/storage';

    public function authBy() {
        return 'admin';
    }

    public function service() {
        return 'storage.fileType';
    }

}
