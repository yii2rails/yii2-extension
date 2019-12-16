<?php

namespace yubundle\storage\domain\v1\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\storage\domain\v1\interfaces\repositories\FileTypeInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class FileTypeRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class FileTypeRepository extends BaseActiveArRepository implements FileTypeInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'storage_file_type';
    }
}
