<?php

namespace yubundle\storage\domain\v1\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\storage\domain\v1\interfaces\repositories\FileExtensionInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class FileExtensionRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class FileExtensionRepository extends BaseActiveArRepository implements FileExtensionInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'storage_file_extension';
    }
}
