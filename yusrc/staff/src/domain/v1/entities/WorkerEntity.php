<?php

namespace yubundle\staff\domain\v1\entities;

use domain\mail\v1\entities\BoxEntity;
use domain\mail\v1\entities\DomainEntity;
use yii\helpers\ArrayHelper;
use yii2bundle\geo\domain\validators\PhoneValidator;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\behaviors\entity\TimeValueFilter;
use yii2rails\domain\data\Query;
use yii2rails\domain\values\TimeValue;
use yii2rails\extension\common\enums\StatusEnum;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\reference\domain\entities\ItemEntity;
use yubundle\user\domain\v1\entities\PersonEntity;

/**
 * Class WorkerEntity
 *
 * @package yubundle\staff\domain\v1\entities
 *
 * @property $id
 * @property $person_id
 * @property $company_id
 * @property $email
 * @property $post_id
 * @property $division_id
 * @property $office
 * @property $phone
 * @property $status
 * @property $created_at
 * @property $updated_at
 * @property ItemEntity $post
 * @property DivisionEntity $division
 * @property PersonEntity $person
 * @property LoginEntity $user
 * @property $full_name
 * @property $corporate_phone
 */
class WorkerEntity extends BaseEntity
{

    protected $id;
    protected $person_id;
    protected $company_id;
    protected $email;
    protected $post_id;
    protected $division_id;
    protected $office;
    protected $phone;
    protected $status = StatusEnum::ENABLE;
    protected $created_at;
    protected $updated_at;
    protected $person;
    protected $division;
    protected $post;
    protected $user;
    protected $corporate_email;
    protected $full_name;
    protected $division_name;
    protected $post_name;
    protected $corporate_phone;

    public function init()
    {
        parent::init();
        $this->hideAttributes(['person', 'division', 'post']);
    }

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
            'person_id' => 'integer',
            'post_id' => 'integer',
            'division_id' => 'integer',
            'company_id' => 'integer',
            'status' => 'integer',
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
            'post' => ItemEntity::class,
            'division' => DivisionEntity::class,
            'person' => PersonEntity::class
        ];
    }

    public function rules()
    {
        return [
            [['email'], 'trim'],
            [['email'], 'email'],
            [['corporate_phone'], 'number'],
            [['person_id', 'post_id', 'division_id'], 'required'],
        ];
    }

    /**
     * @deprecated use email
     */
    public function getCorporateEmail()
    {
        return $this->email;
    }

    public function ___getCorporateEmail()
    {
        $login = ArrayHelper::getValue($this, 'person.user.login');

        $query = new Query;
        $query->where([
            'company_id' => $this->company_id,
        ]);
        /** @var DomainEntity[] $domainCollection */
        $domainCollection = \App::$domain->mail->companyDomain->all($query);
        $domainIds = ArrayHelper::getColumn($domainCollection, 'id');

        $query = new Query;
        $query->where([
            'person_id' => $this->id,
            'domain_id' => $domainIds,
        ]);
        /** @var BoxEntity $boxEntity */
        $domainEntity = \App::$domain->mail->box->one($query);
        return $domainEntity->email;
    }

    public function getPostName() {
        return ArrayHelper::getValue($this, 'post.value');
    }

    public function getDivisionName() {
        return ArrayHelper::getValue($this, 'division.name');
    }

    public function getFullName() {
        return ArrayHelper::getValue($this, 'person.full_name');
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['corporate_email'] = 'corporate_email';
        $fields['full_name'] = 'full_name';
        return $fields;
    }



}
