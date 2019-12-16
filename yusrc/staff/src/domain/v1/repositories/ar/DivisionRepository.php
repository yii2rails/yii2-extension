<?php

namespace yubundle\staff\domain\v1\repositories\ar;

use yubundle\staff\domain\v1\interfaces\repositories\DivisionInterface;
use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2rails\domain\data\Query;

/**
 * Class DivisionRepository
 * 
 * @package yubundle\staff\domain\v1\repositories\ar
 * 
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 */
class DivisionRepository extends BaseActiveArRepository implements DivisionInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'staff_division';
    }

    public function checkExistDivisionByNameAncCompanyId($name, $companyId) {
        $query = new Query();
        $query->andWhere(['name' => $name]);
        $query->andWhere(['company_id' => $companyId]);
        $divisionEntity = \App::$domain->staff->division->repository->one($query);
        return $divisionEntity;
    }

}
