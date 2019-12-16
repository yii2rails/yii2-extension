<?php

namespace yubundle\staff\domain\v1\repositories\ar;

use yii2rails\domain\data\Query;
use yubundle\staff\domain\v1\interfaces\repositories\CompanyInterface;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;

/**
 * Class CompanyRepository
 * 
 * @package yubundle\staff\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 */
class CompanyRepository extends BaseActiveArRepository implements CompanyInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'company';
    }

    public function checkExistsCompanyByName($companyName) {
        $query = new Query();
        $query->andWhere(['name' => $companyName]);
        $companyEntity = \App::$domain->staff->company->repository->one($query);
        return $companyEntity;
    }
}
