<?php

namespace yubundle\account\domain\v2\chain;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\chain\BaseHandler;
use yubundle\reference\domain\entities\ItemEntity;

class PostHandler extends BaseHandler {

    public function get($request) {
        $bookEntity = $request['entities']['book'];
        $postValue = ArrayHelper::getValue($request, 'description');
        try {
            $itemEntity = \App::$domain->reference->item->repository->checkExistsItemByValueAndBookId($postValue, $bookEntity->id);
        } catch (NotFoundHttpException $e) {
            $itemEntity = new ItemEntity();
            $itemEntity->value = $postValue;
            $itemEntity->short_value = $postValue;
            $itemEntity->entity = $postValue;
            $itemEntity->reference_book_id = $bookEntity->id;
            $itemEntity = \App::$domain->reference->item->repository->insert($itemEntity);
        }
        $request['entities']['item'] = $itemEntity;
        $workerHandler = new WorkerHandler();
        $this->setNextHandler($workerHandler);
        $this->nextHandle($request);
    }

}
