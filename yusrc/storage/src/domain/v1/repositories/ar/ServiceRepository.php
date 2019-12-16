<?php

namespace yubundle\storage\domain\v1\repositories\ar;

use App;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\storage\domain\v1\entities\ServiceEntity;
use yubundle\storage\domain\v1\interfaces\repositories\ServiceInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class ServiceRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class ServiceRepository extends BaseActiveArRepository implements ServiceInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'storage_service';
    }

    public function oneByDir(string $dir, Query $query = null) : ServiceEntity {
        $query = Query::forge($query);
        preg_match('#(.*)(\/\d)#', $dir, $matches);
        if (key_exists(1, $matches)) {
            $search = $matches[1];
            $query->andWhere(['path' => $search]);
            $serviceEntity = App::$domain->storage->service->one($query);
            return $serviceEntity;
        } else {
            throw new NotFoundHttpException('Storage service not found!');
        }
    }

}
