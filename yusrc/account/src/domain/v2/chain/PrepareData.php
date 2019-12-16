<?php

namespace yubundle\account\domain\v2\chain;

use yii\helpers\ArrayHelper;
use yubundle\account\domain\v2\chain\BaseHandler;
use yubundle\account\domain\v2\forms\registration\PersonInfoForm;
use yubundle\account\domain\v2\chain\CompanyHandler;

class PrepareData extends BaseHandler {

    public function get($request) {
        $ldapData = $this->getProperties($request);
        $companyHandler = new CompanyHandler();
        $this->setNextHandler($companyHandler);
        $this->nextHandle($ldapData);
    }

    private function getProperties($properties) {
        $filteredProperties = array_filter($properties);

        $defaultProperties = [
            "full_name" => "Имя Фамилия",
            "last_name" => "Фамилия",
            "name" => "Имя",
            "country_code" => "Код страны",
            "country_name" => "Название страны",
            "region" => "Название региона",
            "street_address" => "Адресс",
            "title" => null,
            "description" => "Должность",
            "postal_code" => "asdfasdfazds",
            "post_officebox" => "asdfadsf",
            "telephone_number" => "+77777777777",
            "ip_phone" => "127.0.0.1",
            "home_phone" => "+77777777777",
            "mobile" => "+77777777777",
            "when_created" => null,
            "when_changed" => null,
            "company" => "Название компании",
            "department" => "Остальные",
            "user_principalname" => "user@yumail.local",
            "mail" => "user@yuwert.kz",
            "birthday_day" => date('d'),
            "birthday_month" => date('m'),
            "birthday_year" =>  date('Y'),
        ];

        return ArrayHelper::merge($defaultProperties, $filteredProperties);
    }
}
