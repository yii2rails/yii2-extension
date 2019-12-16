<?php

namespace yubundle\storage\domain\v1\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\storage\domain\v1\interfaces\repositories\FileImageInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class FileImageRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class FileImageRepository extends BaseActiveArRepository implements FileImageInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'storage_image';
    }
}
