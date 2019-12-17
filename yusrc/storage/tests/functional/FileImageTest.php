<?php

namespace tests\functional;

use yii2tool\test\Test\BaseActiveDomainTest;

class FileImageTest extends BaseActiveDomainTest
{

    public $package = 'vendor/yii2rails/yii2-extension/yusrc/storage';

    public function relations() {
        return [
            'file.service.thumbs.ext',
            'file.editor.user',
            'file.ext.type',
        ];
    }

    public function authBy() {
        return 'admin';
    }

    public function service() {
        return 'storage.fileImage';
    }

}
