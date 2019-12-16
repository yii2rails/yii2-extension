<?php

namespace yubundle\account\domain\v2\chain;

use yii\web\NotFoundHttpException;
use yubundle\account\domain\v2\chain\BaseHandler;
use yubundle\account\domain\v2\chain\DivisionHandler;
use yii2rails\domain\data\Query;
use yubundle\reference\domain\entities\BookEntity;

class BookHandler extends BaseHandler {

    public function get($request) {
        $companyEntity = $request['entities']['company'];
        try {
            $bookEntity = \App::$domain->reference->book->repository->checkExistsBookByCompanyId($companyEntity->id);
        } catch (NotFoundHttpException $e) {
            $bookEntity = new BookEntity();
            $bookEntity->name = $companyEntity->name;
            $bookEntity->entity = 'posts';
            $bookEntity->owner_id = $companyEntity->id;
            $bookEntity->levels = 1;
            $bookEntity = \App::$domain->reference->book->repository->insert($bookEntity);
        }
        $request['entities']['book'] = $bookEntity;
        $divisionHandler = new DivisionHandler();
        $this->setNextHandler($divisionHandler);
        $this->nextHandle($request);
    }

}
