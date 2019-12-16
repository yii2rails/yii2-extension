<?php

namespace yubundle\staff\domain\v1\entities;

use App;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\behaviors\entity\TimeValueFilter;
use yii2rails\domain\data\Query;
use yii2rails\domain\values\TimeValue;

/**
 * Class DivisionEntity
 * 
 * @package yubundle\staff\domain\v1\entities
 * 
 * @property $id
 * @property $parent_id
 * @property $company_id
 * @property $name
 * @property $status
 * @property $created_at
 * @property $updated_at
 * @property DivisionEntity $parent
 * @property DivisionEntity[] $child
 * @property CompanyEntity $company
 * @property WorkerEntity[] $workers
 */
class DivisionEntity extends BaseEntity {

	protected $id;
	protected $parent_id;
	protected $company_id;
	protected $name;
	protected $status = 1;
	protected $created_at;
	protected $updated_at;
    protected $parent;
    protected $child;
    protected $company;
    protected $workers;

    public function behaviors() {
        return [
            [
                'class' => TimeValueFilter::class,
            ],
        ];
    }

    public function fieldType()
    {
        return [
            'id' => 'integer',
            'company_id' => 'integer',
            'status' => 'integer',
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['company_id', 'name'], 'required'],
            [['name'], 'uniqueDivision'],
            [['name'], 'match', 'pattern' => '/^[\w0-9\s.]+$/u','message'=>'Разрешены только буквы и цифры'],
        ];
    }

    public function uniqueDivision()
    {
        $query = Query::forge();
        $query->where(['name'=>$this->name]);

        $countRecords = App::$domain->staff->division->count($query);

        if ($countRecords > 0) {
            $this->addError('name', 'Данное подразделение уже есть.');
        }
    }

}
