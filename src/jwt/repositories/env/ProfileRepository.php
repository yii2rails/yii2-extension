<?php

namespace yii2rails\extension\jwt\repositories\env;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\data\Query;
use yii2rails\extension\arrayTools\traits\ArrayReadTrait;
use yii2rails\extension\jwt\interfaces\repositories\ProfileInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class ProfileRepository
 * 
 * @package yii2rails\extension\jwt\repositories\memory
 * 
 * @property-read \yii2rails\extension\jwt\Domain $domain
 */
class ProfileRepository extends BaseRepository implements ProfileInterface {

    use ArrayReadTrait;

	//protected $schemaClass = true;
    protected $primaryKey = 'id';

    protected function getCollection()
    {
        $envProfiles = EnvService::get('jwt.profiles', []);
        $profiles = [];
        foreach ($envProfiles as $name => $config) {
            $config['id'] = $name;
            $config['name'] = $name;
            $profiles[$name] = $config;
        }
        return $profiles;
    }

}
