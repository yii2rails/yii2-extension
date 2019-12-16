<?php

namespace yubundle\storage\domain\v1\interfaces\services;

use yubundle\storage\domain\v1\entities\FileEntity;

/**
 * Interface StaticInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\StaticInterface $repository
 */
interface StaticInterface {

    public function getFileEntityByFilePath(string $filePath) : FileEntity;

}
