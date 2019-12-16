<?php

namespace yubundle\storage\domain\v1\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;
use yubundle\storage\admin\forms\UploadForm;
use yubundle\storage\domain\v1\entities\FileEntity;

/**
 * Interface AttachmentInterface
 * 
 * @package yubundle\storage\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileInterface $repository
 */
interface AttachmentInterface extends CrudInterface {

    public function uploadOne(UploadForm $model) : FileEntity;
    public function deleteOne(int $id);

}
