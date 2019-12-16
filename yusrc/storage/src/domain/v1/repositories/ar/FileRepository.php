<?php

namespace yubundle\storage\domain\v1\repositories\ar;

use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\interfaces\repositories\FileInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class FileRepository
 * 
 * @package yubundle\storage\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 */
class FileRepository extends BaseActiveArRepository implements FileInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'storage_file';
    }

    public function oneByServiceIdAndEntityId($serviceId, int $entityId, Query $query = null) : FileEntity {
        $query = new Query;
        $query->with('service');
        $query->andWhere([
            'service_id' => $serviceId,
            'entity_id' => $entityId,
        ]);
        return $this->one($query);
    }

    public function oneByServiceIdAndEntityIdAndFileName($serviceId, int $entityId,string $fileName, Query $query = null) : FileEntity {
        $query = new Query;
        $query->with('service');
        $query->andWhere([
            'service_id' => $serviceId,
            'entity_id' => $entityId,
            'name' => $fileName,
        ]);
        return $this->one($query);
    }

    public function oneByServiceIdAndEntityIdAndFileHash($serviceId, int $entityId, string $fileHash, Query $query = null) : FileEntity {
        $query = new Query;
        $query->with('service');
        $query->andWhere([
            'service_id' => $serviceId,
            'entity_id' => $entityId,
            'hash' => $fileHash,
        ]);
        return $this->one($query);
    }

    public function oneByHashAndEntityId($hash, int $entityId, Query $query = null) : FileEntity {
        $query = new Query;
        $query->andWhere([
            'hash' => $hash,
            'entity_id' => $entityId,
        ]);
        return $this->one($query);
    }
}
