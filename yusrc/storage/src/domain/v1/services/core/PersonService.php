<?php

namespace yubundle\storage\domain\v1\services\core;

use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\helpers\RestHelper;
use yii2lab\rest\domain\traits\RestTrait;
use yii2rails\domain\behaviors\query\QueryFilter;
use yii2rails\domain\data\Query;
use yii2rails\domain\enums\ActiveMethodEnum;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\core\domain\helpers\CoreHelper;
use yii2rails\extension\core\domain\helpers\CoreRequestHelper;
use yii2rails\extension\core\domain\services\base\BaseActiveCoreService;
use yii2rails\extension\core\domain\services\base\BaseCoreService;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yubundle\account\domain\v2\helpers\AuthHelper;
use yubundle\storage\admin\forms\UploadForm;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\storage\domain\v1\interfaces\services\PersonInterface;
use yii2rails\domain\services\base\BaseActiveService;
use yii\web\NotFoundHttpException;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yubundle\storage\domain\v1\entities\FileEntity;
use yubundle\storage\domain\v1\entities\StorageEntity;
use yubundle\storage\domain\v1\interfaces\services\StorageInterface;
use yii\web\UploadedFile;
use yii2rails\domain\services\base\BaseService;
use yii2rails\extension\common\helpers\TempHelper;

/**
 * Class PersonService
 *
 * @package yubundle\storage\domain\v1\services
 *
 * @property-read \yubundle\storage\domain\v1\Domain $domain
 * @property-read \yubundle\storage\domain\v1\interfaces\repositories\PersonInterface $repository
 */
class PersonService extends BaseActiveCoreService implements PersonInterface {

    use RestTrait;

    public function getRepository($name = null)
    {
        return $this->domain->repositories->file;
    }

    public function sort() {
		return [
			'attributes' => [
				'id',
				'size',
				'url',
			],
		];
	}

    public function saveUploaded(UploadForm $model) {
        try {
            $collection = $this->saveUploadedCollection(['file' => $model->file], $model->service_id);
        } catch (UnprocessableEntityHttpException $e) {
            $error = new ErrorCollection;
            foreach ($e->getErrors() as $err) {
                $error->add($err['field'], $err['message']);
            }
            throw new UnprocessableEntityHttpException($error);
        }
        return $collection[0];

        /*$tempFile = TempHelper::copyUploadedToTemp($model->file);
        $requestEntity = new RequestEntity;
        $requestEntity->uri = 'http://api.storage.srv/v1/file-storage';
        $requestEntity->method = HttpMethodEnum::POST;
        $requestEntity->data = [
            'service_id' => $model->service_id,
        ];
        $requestEntity->files = [
            'file' => $tempFile,
        ];
        $responseEntity = CoreRequestHelper::sendRequest($requestEntity);
        return Helper::forgeEntity($responseEntity->data, FileEntity::class, true);*/
    }

    public function saveUploadedCollection(array $uploadedCollection, int $serviceId) : array {
        /** @var UploadedFile[] $uploadedCollection */
        /** @var FileEntity[] $collection */
        $requestEntity = new RequestEntity;
        $requestEntity->uri = StorageHelper::forgeApiUrl('v1/file-storage');
        $requestEntity->method = HttpMethodEnum::POST;
        $requestEntity->data = [
            'service_id' => $serviceId,
        ];
        $files = [];
        foreach ($uploadedCollection as $name => $uploadedFile) {
            $tempFile = TempHelper::copyUploadedToTemp($uploadedFile);
            $files[$name] = $tempFile;
        }
        $requestEntity->files = $files;
        $responseEntity = CoreRequestHelper::sendRequest($requestEntity);
        $collection = Helper::forgeEntity($responseEntity->data, FileEntity::class, true);
        return $collection;
    }

}
