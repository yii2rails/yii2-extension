Attachment
===

Для подключения вложений к сущности необходимо сделать следующее.

## Объявить сервис в вашем домене

```php
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		return [
			'services' => [
                'attachment' => [
                    'class' => AttachmentService::class,
                    'serviceCode' => LawStorageServiceEnum::REQUEST_SERVICE_CODE,
                ],
			],
		];
	}
	
}
```

## Объявить связь в схеме

```php
use yubundle\storage\domain\v1\helpers\StorageHelper;

class RequestSchema extends BaseSchema {

    public function relations()
    {
        return [
            'logo' => StorageHelper::generateSchemaForAttachments('storage.file', true,'logo_id', 'id'),
        ];
    }

}
```

## Доработать метод создания в сервисе

```php
class RequestService extends BaseActiveService implements RequestInterface {

    public function create($data)
    {
        $requestEntity = parent::create($data);
        $attachmentId = ArrayHelper::getValue($data, 'attachment_id');
        $attachmentIds = Helper::idsToArray($attachmentId);
        \App::$domain->storage->file->moveAll($attachmentIds, LawStorageServiceEnum::REQUEST_SERVICE_CODE, $requestEntity->id);
        return $requestEntity;
    }

}
```

## Добавить поле в сущности

```php
class RequestEntity extends BaseEntity {

    protected $attachments;

}
```

Добавить в репозиторий 

```
    protected function prepareQuery(Query $query = null)
    {
        $query = parent::prepareQuery($query);
        $query->with('logo.service.thumbs');
        return $query;
    }

```