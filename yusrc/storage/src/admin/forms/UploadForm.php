<?php

namespace yubundle\storage\admin\forms;

use Yii;
use yii\web\UploadedFile;
use yii2rails\domain\base\Model;
use yubundle\storage\domain\v1\validators\FileSizeValidator;

class UploadForm extends Model
{

    public $file;
    public $service_id;
    public $entity_id;
    public $description;

    private $extensions = [];

    public function assignExtensions(array $extensions) {
        $this->extensions = $extensions;
    }

    public function init()
    {
        if(Yii::$app->request->isPost) {
            $this->file = UploadedFile::getInstanceByName('file');
        }
    }

    public function rules()
    {
        return [
            ['file', FileSizeValidator::class],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => implode(',', $this->extensions)],
            [['service_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => Yii::t('main', 'file'),
        ];
    }
}
