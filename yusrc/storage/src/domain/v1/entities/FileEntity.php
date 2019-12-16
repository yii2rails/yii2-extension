<?php

namespace yubundle\storage\domain\v1\entities;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\behaviors\entity\TimeValueFilter;
use yii2rails\domain\values\TimeValue;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\user\domain\v1\entities\PersonEntity;

/**
 * Class FileEntity
 * 
 * @package yubundle\storage\domain\v1\entities
 * 
 * @property $id
 * @property $service_id
 * @property $entity_id
 * @property $editor_id
 * @property $hash
 * @property $extension
 * @property $name
 * @property $size
 * @property $description
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property $directory
 * @property $file_name
 * @property $source_file_name
 * @property $file_path
 * @property $source_file_path
 * @property FileUrlEntity $url
 *
 * @property ServiceEntity $service
 * @property PersonEntity $editor
 * @property FileExtensionEntity $ext
 */
class FileEntity extends BaseEntity {

	protected $id;
	protected $service_id;
    protected $entity_id;
	protected $editor_id;
	protected $hash;
	protected $extension;
    protected $name;
    protected $size;
	protected $description;
	protected $status = 1;
	protected $created_at;
	protected $updated_at;

    protected $directory;
    protected $file_name;
    protected $file_path;
    protected $url;
    protected $thumbUrls;
    protected $source_file_name;
    protected $source_file_path;

    protected $service;
    protected $editor;
    protected $ext;

    public function behaviors() {
        return [
            [
                'class' => TimeValueFilter::class,
            ],
        ];
    }

    public function fieldType()
    {
        return [
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,

            'service' => ServiceEntity::class,
            'editor' => PersonEntity::class,
            'ext' => FileExtensionEntity::class,
        ];
    }

    public function getEntityId() {
        if($this->entity_id === null) {
            return $this->getEditorId();
        }
        return $this->entity_id;
    }

    public function getEditorId() {
        if($this->editor_id === null) {
            $this->editor_id = \App::$domain->account->auth->identity->id;
        }
        return $this->editor_id;
    }

    public function getDirectory() {
        if(empty($this->service)) {
            return null;
        }
        $path = $this->service->path . SL . $this->getEntityId();
        return $path;
    }

    public function getFilePath() {
        $fileName = $this->hash;
        $path = 'storage' . SL . $this->getDirectory() . SL . $fileName . DOT . $this->extension;
        return trim($path, SL);
    }

    public function getSourceFilePath() {
        $fileName = $this->hash;
        $path = 'storage' . SL . $this->getDirectory() . SL . 'source' . SL . $fileName . DOT . $this->extension;
        return trim($path, SL);
    }

    public function getUrl() {
        $path = $this->getFilePath();
        $urlEntity = new FileUrlEntity;
        $urlEntity->constant = StorageHelper::forgeResourceUrl($path);
        $urlEntity->source = $this->getSourceUrl();
        $urlEntity->download = $this->getDownloadUrl();
        return $urlEntity;
    }

    public function getThumbUrls()
    {
        return StorageHelper::generateThumbsUrls($this);
    }

    private function getSourceUrl() {
        $path = $this->getSourceFilePath();
        return StorageHelper::forgeResourceUrl($path);
    }

    private function getDownloadUrl() {
        $url = $this->getSourceUrl();
        return $url . '?action=download';
    }

    public function getFileName() {
        return $this->forgeFileName($this->name);
    }

    protected function forgeFileName($fileName) {
        return $fileName;
    }
}
