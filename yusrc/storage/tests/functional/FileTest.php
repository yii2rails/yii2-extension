<?php

namespace tests\functional;

use yii2tool\test\Test\BaseActiveDomainTest;

class FileTest extends BaseActiveDomainTest
{

    public $package = 'vendor/yii2rails/yii2-extension/yusrc/storage';

    public function relations() {
        return [
            'service.thumbs.ext',
            //'editor.worker.person.user',

            'editor.user.person',
            //'editor.user.company.domains',
            'editor.user.assignments',
            'editor.user.security',

            'ext.type',
        ];
    }

    public function authBy() {
        return 'admin';
    }

    public function service() {
        return 'storage.file';
    }

}
