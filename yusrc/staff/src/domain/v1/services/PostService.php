<?php

namespace yubundle\staff\domain\v1\services;

use yii2rails\domain\data\Query;
use yubundle\reference\domain\entities\BookEntity;
use yubundle\reference\domain\services\EnumService;
use yubundle\staff\domain\v1\interfaces\services\PostInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class PostService
 * 
 * @package yubundle\staff\domain\v1\services
 * 
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 * @property-read \yubundle\staff\domain\v1\interfaces\repositories\PostInterface $repository
 */
class PostService extends EnumService implements PostInterface {

    public $referenceBookId;

    public function init()
    {
        if(empty($this->referenceBookId)) {
            $bookEntity = $this->getPostBook();
            $this->referenceBookId = $bookEntity->id;
        }
        parent::init();
    }

    private function getPostBook() : BookEntity {
        $companyId = \App::$domain->account->auth->identity->company_id;
        $query = new Query;
        $query->andWhere([
            'or',
            [
                'owner_id' => $companyId,
                'entity' => 'posts',
            ],
            [
                'entity' => 'posts_' . $companyId,
            ],
        ]);
        /** @var BookEntity $bookEntity */
        $bookEntity = \App::$domain->reference->book->one($query);
        return $bookEntity;
    }
    
}
