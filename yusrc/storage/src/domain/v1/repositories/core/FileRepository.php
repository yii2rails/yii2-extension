<?php

namespace yubundle\storage\domain\v1\repositories\core;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2rails\extension\core\domain\repositories\base\BaseActiveCoreRepository;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\storage\domain\v1\interfaces\repositories\FileInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class FileRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class FileRepository extends BaseActiveCoreRepository implements FileInterface {

    public $point = 'file-storage';
    public $version = 1;

    protected function initBaseUrl() {
        $uri = 'v' . $this->version . SL . $this->point;
        $this->baseUrl = StorageHelper::forgeApiUrl($uri);
    }

    public function oneByHash($hash, Query $query = null) {
        $query = new Query;
        $query->andWhere(['hash' => $hash]);
        return $this->one($query);
    }

}
