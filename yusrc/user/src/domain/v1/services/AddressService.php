<?php

namespace yubundle\user\domain\v1\services;

use App;
use yubundle\user\domain\v1\entities\AddressEntity;
use yubundle\user\domain\v1\interfaces\services\AddressInterface;
use yii\web\NotFoundHttpException;
use yii2mod\helpers\ArrayHelper;
use yii2rails\domain\data\Query;
use yii2rails\domain\services\base\BaseService;

/**
 * Class AddressService
 *
 * @package yubundle\user\domain\v1\services
 *
 * @property-read \yubundle\user\domain\v1\Domain $domain
 * @property-read \yubundle\user\domain\v1\interfaces\repositories\AddressInterface $repository
 */
class AddressService extends BaseService implements AddressInterface
{

    public function myEmail() : string
    {
        $loginEntity = App::$domain->account->auth->identity;
        $domainQuery = new Query;
        $domainQuery->andWhere(['company_id' => $loginEntity->company_id]);
        $domainEntity = App::$domain->mail->companyDomain->one($domainQuery);
        $boxQuery = new Query;
        $boxQuery->andWhere([
            'domain_id' => $domainEntity->id,
            'person_id' => $loginEntity->person_id,
        ]);
        $boxEntity = App::$domain->mail->box->one($boxQuery);
        return $boxEntity->email;
    }

    public function myAddress(): AddressEntity
    {
        $email = $this->myEmail();
        return $this->parseEmail($email);
    }

    public function oneByEmail(string $email): AddressEntity
    {
        list($login, $domain) = explode('@', $email);

        if ($domain != 'yuwert.kz') {
            //throw new NotFoundHttpException("Domain \"$domain\" not found!");
        } else {
            try {
                \App::$domain->account->login->oneByLogin($login);
            } catch (NotFoundHttpException $e) {
                throw new NotFoundHttpException("Email \"$email\" not found!", 0, $e);
            }
        }
        $addressEntity = new AddressEntity;
        $addressEntity->domain = $domain;
        $addressEntity->login = $login;
        return $addressEntity;
    }

    public function parseEmail(string $email): AddressEntity
    {
        list($login, $domain) = explode('@', $email);
        $addressEntity = new AddressEntity;
        $addressEntity->domain = $domain;
        $addressEntity->login = $login;
        return $addressEntity;
    }

    public function isInternal(string $email): bool
    {
        $addressEntity = $this->parseEmail($email);
         return $addressEntity->domain != 'yuwert.kz';
    }

    public function personIdByEmail($email) {
        $personIdList = $this->personIdsByEmails($email);
        $personId = ArrayHelper::first($personIdList);
        return $personId;
    }

    public function personIdsByEmails($emails) {
        $emails = ArrayHelper::toArray($emails);
        $loginList = $this->getLoginListByContractorEmailList($emails);
        $personIdList = $this->getPersonIdListByLoginList($loginList);
        return $personIdList;
    }

    private function getLoginListByContractorEmailList($contractorEmailList) {
        if(empty($contractorEmailList)) {
            return [];
        }
        $loginList = [];
        foreach ($contractorEmailList as $contractorEmail) {
            $addressEntity = App::$domain->mail->address->oneByEmail($contractorEmail);
            if($addressEntity->domain == 'yuwert.kz') {
                $loginList[] = $addressEntity->login;
            }
        }
        return $loginList;
    }

    private function getPersonIdListByLoginList($loginList) {
        if(empty($loginList)) {
            return [];
        }
        $query = new Query;
        $query->andWhere(['login' => $loginList]);
        $loginCollection = App::$domain->account->login->all($query);
        if(empty($loginCollection)) {
            return [];
        }
        $personIdList = ArrayHelper::getColumn($loginCollection, 'person_id');
        return $personIdList;
    }

}
