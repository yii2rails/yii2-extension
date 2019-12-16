<?php

namespace yubundle\account\domain\v2\helpers;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yubundle\account\domain\v2\chain\AccountHandler;

class LDAPHelper extends Component {

    public $port;
    private $timeout;
    private $server;
    private $adminLogin;
    private $adminPassword;
    private $dn;
    //Search query. CN - common name (CN=* will return all objects)
    private $search;
    private $domain;
    private $userDomain;

    private $userAttributeCollection = [
        'cn' => null,
        'sn' => null,
        'c' => null,
        'l' => null,
        'st' => null,
        'title' => null,
        'description' => null,
        'postalCode' => null,
        'postOfficeBox' => null,
        'telephoneNumber' => null,
        'facsimileTelephoneNumber' => null,
        'givenName' => null,
        'instanceType' => null,
        'whenCreated' => null,
        'whenChanged' => null,
        'displayName' => null,
        'co' => null,
        'department' => null,
        'company' => null,
        'streetAddress' => null,
        'name' => null,
        'userAccountControl' => null,
        'badPwdCount' => null,
        'codePage' => null,
        'countryCode' => null,
        'primaryGroupID' => null,
        'logonCount' => null,
        'samaccountname' => null,
        'sAMAccountType' => null,
        'userPrincipalName' => null,
        'ipPhone' => null,
        'objectCategory' => null,
        'dSCorePropagationData' => null,
        'mail' => null,
        'homePhone' => null,
        'mobile' => null,
        'pager' => null
    ];

    public function __construct() {
        $this->port = EnvService::get('servers.ldap.port');
        $this->timeout = EnvService::get('servers.ldap.timeout');
        $this->server = EnvService::get('servers.ldap.server');
        $this->adminLogin = EnvService::get('servers.ldap.adminLogin');
        $this->adminPassword = EnvService::get('servers.ldap.adminPassword');
        $this->dn = EnvService::get('servers.ldap.dn');
        $this->search = EnvService::get('servers.ldap.search');
        $this->domain = EnvService::get('servers.ldap.domain');
        $this->userDomain = EnvService::get('servers.ldap.userDomain');
        $this->userDomain =  '@' . $this->domain . '.local';
    }

    private function loadData() {
        $connection = $this->connect();
        $this->auth($connection,  $this->adminLogin, $this->adminPassword);
        $activeDirectoryAttributeCollection = $this->getActiveDirectoryAttributeCollection();
        $searchFilter = "mail=*";
        $dn = "cn=Users, dc=yumail, dc=local";
        $entryCollection = $this->search($connection, $dn, $searchFilter, $activeDirectoryAttributeCollection);

        $userdataCollection = [];
        $userdata = null;
        foreach ($entryCollection as $row) {
            if (is_array($row) && count($row) > 0) {
                $userdata = $this->userAttributeCollection;
                foreach ($row as $key => $param) {
                    if (is_int($key)) {
                        continue;
                    }
                    if (in_array($key, $activeDirectoryAttributeCollection)) {
                        /*
                            //TODO : это всё из-за того, что работаем под Linux
                            $str = $param[0];
                            $str = "$str";
                            $userdata[$key] = mb_convert_encoding($str, 'utf-8', 'cp1251');
                        */
                        $userdata[$key] = $param[0];
                    }
                }
                $userdataCollection[] = $userdata;
            }
        }
        ldap_close($connection);
        return $userdataCollection;
    }

    public function login($login = null, $password = null) {
        $connection = $this->connect();
        //Если логин и пароль верный
        $this->auth($connection, $login, $password, $this->domain);
        //Устанавливаем фильтры и атрибуты
        $searchFilter = "(samAccountName=*" . $login . "*)";

        $activeDirectoryAttributeCollection = $this->getActiveDirectoryAttributeCollection();
        $dn = 'DC='. $this->domain .', DC=local';
        $entryCollection = $this->search($connection, $dn, $searchFilter, $activeDirectoryAttributeCollection);

        $userdata = $this->userAttributeCollection;
        foreach ($entryCollection[0] as $key => $value) {
            if (is_int($key)) {
                continue;
            } else if (in_array($key, $activeDirectoryAttributeCollection)) {
                /*
                    //TODO : это всё из-за того, что работаем под Linux
                    $str = $param[0];
                    $str = "$str";
                    $userdata[$key] = mb_convert_encoding($str, 'utf-8', 'cp1251');
                */
                $userdata[$key] = $value[0];
            }
        }
        return $userdata;
    }

    public function loadUserdata() {
        $ldapData = $this->loadData();
        foreach ($ldapData as $data) {
            $userdata = [];
            $userdata['login'] = $data['samaccountname'];
            $userdata['password'] = 'Yuwert2018!';
            $userdata['ip'] = '127.0.0.1';
            $userdata['action'] = 'create';

            $userdata['full_name'] = $data['cn'];
            $userdata['last_name'] = $data['sn'];
            $userdata['name'] = $data['name'];
            $userdata['country_code'] = $data['c'];
            $userdata['country_name'] = $data['co'];
            $userdata['region'] = $data['st'];
            $userdata['title'] =  $data['title'];
            $userdata['description'] = $data['description'];
            $userdata['postal_code'] = $data['postalCode'];
            $userdata['post_officebox'] = $data['postOfficeBox'];
            $userdata['telephone_number'] = $data['telephoneNumber'];
            $userdata['ip_phone'] = $data['ipPhone'];
            $userdata['home_phone'] = $data['homePhone'];
            $userdata['mobile'] = $data['mobile'];
            $userdata['when_created'] = $data['whenCreated'];
            $userdata['when_changed'] = $data['whenChanged'];
            $userdata['company'] = $data['company'];
            $userdata['department'] = $data['department'];
            $userdata['user_principalname'] = $data['userPrincipalName'];
            $userdata['mail'] = $data['mail'];
            $authenticationHandler = new AccountHandler();
            $loginEntity = $authenticationHandler->get($userdata);
        }
    }

    private function getActiveDirectoryAttributeCollection() {
        $activeDirectoryAttributeCollection = [];
        foreach ($this->userAttributeCollection as $key => $attribute) {
            $activeDirectoryAttributeCollection[] = $key;
        }
        return $activeDirectoryAttributeCollection;
    }

    private function connect() {
        $checkConnection = fsockopen( $this->server, $this->port, $errno, $errstr, $this->timeout);
        if ($checkConnection) {
            $connection = ldap_connect($this->server);
            ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
            return $connection;
        } else {
            $error = new ErrorCollection();
            $error->add('post', 'Ошибка при подключении к Active Directoy');
            throw new UnprocessableEntityHttpException($error);
        }
    }

    private function auth($connection, $login = null, $password = null, $domain = '') {
        if (isset($login) && isset($password)) {
            try {
                $login = $login . $this->userDomain;
                ldap_bind($connection, $login, $password);
            } catch (\Exception $e) {
                $error = new ErrorCollection();
                $error->add('ldap', 'Неверный пароль или логин');
                throw new UnprocessableEntityHttpException($error);
            }
        } else {
            $error = new ErrorCollection();
            $error->add('ldap', 'Переданные параметры равны null или false');
            throw new UnprocessableEntityHttpException($error);
        }
    }

    private function search($connection, $dn, $searchFilter, $attributeCollection) {
        $query = ldap_search($connection, $dn, $searchFilter, $attributeCollection) or die ("Error in search query");
        $entryCollection = ldap_get_entries($connection, $query);
        return $entryCollection;
    }

}