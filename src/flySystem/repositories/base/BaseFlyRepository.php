<?php

namespace yii2rails\extension\flySystem\repositories\base;

use creocoder\flysystem\Filesystem;
use Yii;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\repositories\BaseRepository;

abstract class BaseFlyRepository extends BaseRepository {
	
	public $pathName = '';
	
	/**
	 * @var Filesystem
	 */
	private $storeInstance;

    protected function hasFile($fileName) {
        $staticFs = $this->storeInstance();
        $file = $this->fullName($fileName);
        return $staticFs->has($file, $content);
    }

    protected function fileList($dir) {
        $staticFs = $this->storeInstance();
        return $staticFs->listContents($dir);
    }

    protected function getMetadata($fileName) {
        $staticFs = $this->storeInstance();
        $file = $this->fullName($fileName);
        return $staticFs->getMetadata($file);
    }

	protected function writeFile($fileName, $content) {
		$this->removeFile($fileName);
		$staticFs = $this->storeInstance();
		$file = $this->fullName($fileName);
		$staticFs->write($file, $content);
		$this->writeDirectoryIndex(dirname($file));
	}

	protected function writeDirectoryIndex($directory) {
        $staticFs = $this->storeInstance();
        try {
            $staticFs->write($directory . SL . 'index.html', 'Forbidden!');
        } catch (\Exception $e) {}
    }

    protected function moveFile($fileName, $targetFileName) {
        $staticFs = $this->storeInstance();
        $file = $this->fullName($fileName);
        $taregtFile = $this->fullName($targetFileName);
        if($staticFs->has($file)) {
            $staticFs->rename($file, $taregtFile);
        }
    }

	protected function removeFile($fileName) {
		$staticFs = $this->storeInstance();
		$file = $this->fullName($fileName);
		if($staticFs->has($file)) {
			$staticFs->delete($file);
		}
	}
	
	private function fullName($name) {
		$file = $this->pathName . SL . $name;
		$file = str_replace(BSL, SL, $file);
		return $file;
	}
	
	private function storeInstance() {
		if(!$this->storeInstance instanceof Filesystem) {
			$this->initStoreInstance();
		}
		return $this->storeInstance;
	}
	
	private function initStoreInstance() {
		$definition = EnvService::get('servers.static.connection');
		$driver = EnvService::get('servers.static.driver');
		$driver = ucfirst($driver);
		$definition['class'] = 'creocoder\flysystem\\' . $driver . 'Filesystem';
		$this->storeInstance = Yii::createObject($definition);
		return $this->storeInstance;
	}
	
}