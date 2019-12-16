<?php

namespace yubundle\staff\domain\v1\services;

use yubundle\staff\domain\v1\interfaces\services\CompanyInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class CompanyService
 * 
 * @package yubundle\staff\domain\v1\services
 * 
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 * @property-read \yubundle\staff\domain\v1\interfaces\repositories\CompanyInterface $repository
 */
class CompanyService extends BaseActiveService implements CompanyInterface {

    public function create($data)
    {
        $newCompany = parent::create($data);
        $book = [
            'name' => $data['name'] . ' posts',
            'levels' => 1,
            'entity' => 'posts_' . $newCompany->id,
            'owner_id' => $newCompany->id
        ];
        $book = \App::$domain->reference->book->create($book);

        return $newCompany;
    }

}
