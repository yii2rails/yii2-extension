<?php

namespace tests\functional;

use yii2tool\test\Test\BaseActiveDomainTest;

class FileExtensionTest extends BaseActiveDomainTest
{

    public $package = 'vendor/yubundle/yii2-storage';

    public function relations() {
        return [
            'type',
        ];
    }

    public function authBy() {
        return 'admin';
    }

    public function service() {
        return 'storage.fileExtension';
    }

}
