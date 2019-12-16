<?php

namespace yubundle\storage\domain\v1\services;

use yii\base\InvalidConfigException;
use yii2lab\applicationTemplate\backend\assets\AppAsset;
use yii2rails\domain\data\Query;
use yubundle\storage\admin\forms\UploadForm;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\interfaces\services\AttachmentInterface;
use yubundle\storage\domain\v1\interfaces\services\FileInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class AttachmentService
 * 
 * @package yubundle\storage\domain\v1\services
 * 
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\FileInterface $repository
 */
class AttachmentService extends BaseActiveService implements AttachmentInterface {

    public $serviceCode;

    public function getRepository($name = null)
    {
        return \App::$domain->storage->repositories->file;
    }

    public function uploadOne(UploadForm $model) : FileEntity
    {
        $serviceEntity = \App::$domain->storage->service->oneByCode($this->serviceCode);
        $model->service_id = $serviceEntity->id;
        $fileEntity = \App::$domain->storage->person->saveUploadedNew($model);
        return $fileEntity;
    }

    public function deleteOne(int $id) {
        $serviceEntity = \App::$domain->storage->service->oneByCode($this->serviceCode);
        $fileEntity = \App::$domain->storage->file->oneByServiceIdAndEntityId($serviceEntity->id, $id);
        \App::$domain->storage->file->deleteById($fileEntity->id);
    }

    protected function prepareQuery(Query $query = null)
    {
        if(empty($this->serviceCode)) {
            throw new InvalidConfigException('Configure serviceCode in AttachmentService');
        }
        $query = parent::prepareQuery($query);
        $serviceEntity = \App::$domain->storage->service->oneByCode($this->serviceCode);
        $query->andWhere(['service_id' => $serviceEntity->id]);
        $query->with('service');
        return $query;
    }

}

/*
 *
Domain config:

class Domain extends \yii2rails\domain\Domain {

	public function config() {
		return [
			'services' => [
                'attachment' => [
                    'class' => AttachmentService::class,
                    'serviceCode' => 'law_request',
                ],
			],
		];
	}

}

Schema:

public function relations()
{
    return [
        'attachments' => [
            'type' => RelationEnum::MANY,
            'field' => 'id',
            'foreign' => [
                'classType' => RelationClassTypeEnum::SERVICE,
                'id' => 'law.attachment',
                'field' => 'entity_id',
            ],
        ],
    ];
}

 */