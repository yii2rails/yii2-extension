<?php

namespace yubundle\storage\domain\v1;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\enums\Driver;

/**
 * Class Domain
 * 
 * @property-read \yubundle\storage\domain\v1\interfaces\services\StorageInterface $storage
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yubundle\storage\domain\v1\interfaces\services\PersonInterface $person
 * @property-read \yubundle\storage\domain\v1\interfaces\services\ServerInterface $server
 * @property-read \yubundle\storage\domain\v1\interfaces\services\FileInterface $file
 * @property-read \yubundle\storage\domain\v1\interfaces\services\FileExtensionInterface $fileExtension
 * @property-read \yubundle\storage\domain\v1\interfaces\services\FileTypeInterface $fileType
 * @property-read \yubundle\storage\domain\v1\interfaces\services\FileImageInterface $fileImage
 * @property-read \yubundle\storage\domain\v1\interfaces\services\ServiceInterface $service
 * @property-read \yubundle\storage\domain\v1\interfaces\services\ServiceThumbInterface $serviceThumb
 * @property-read \yubundle\storage\domain\v1\interfaces\services\StaticInterface $static
 * @property-read \yubundle\storage\domain\v1\interfaces\services\PolicyInterface $policy
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {

        if(EnvService::getServer('storage.driver') == Driver::CORE) {
            $remoteServiceDriver = Driver::CORE;
            $remoteRepositoryDriver = Driver::CORE;
        } else {
            $remoteServiceDriver = null;
            $remoteRepositoryDriver = Driver::ACTIVE_RECORD;
        }

		return [
			'repositories' => [
                'storage' => Driver::FILE,
                //'person' => Driver::FILE,
                'server' => Driver::FILE,
                'file' => $remoteRepositoryDriver,
                'fileExtension' => $remoteRepositoryDriver,
                'fileType' => $remoteRepositoryDriver,
                'fileImage' => $remoteRepositoryDriver,
                'service' => $remoteRepositoryDriver,
                'serviceThumb' => $remoteRepositoryDriver,
                'static' => Driver::FILE,
                'policy' => $remoteRepositoryDriver
			],
			'services' => [
                'person' => $remoteServiceDriver,
                'server',
                'file',
                'fileExtension',
                'fileType',
                'fileImage',
                'service',
                'serviceThumb',
                'static',
                'policy'
			],
		];
	}
	
}