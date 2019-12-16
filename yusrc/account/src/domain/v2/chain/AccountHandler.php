<?php

namespace yubundle\account\domain\v2\chain;


use Yii;
use yii\helpers\ArrayHelper;
use yii2rails\domain\helpers\ErrorCollection;
use yubundle\account\domain\v2\chain\BaseHandler;
use yubundle\account\domain\v2\chain\PrepareData;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\entities\SecurityEntity;
use yubundle\account\domain\v2\forms\ChangePasswordForm;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yubundle\account\domain\v2\helpers\LoginHelper;

class AccountHandler extends BaseHandler {

    public function get($request) {
        $login = ArrayHelper::getValue($request, 'login');
        $password = ArrayHelper::getValue($request, 'password');
        $checkLogin = \App::$domain->account->login->repository->isExistsByLogin($login);
        if ($checkLogin) {
            $query = new Query;
            $query->with(['assignments', 'person', 'company']);
            $loginEntity = \App::$domain->account->login->repository->oneByVirtual($login, $query);
            $actionCreate = ArrayHelper::getValue($request, 'action');

            if (!isset($actionCreate)) {
                try {
                    $securityEntity = \App::$domain->account->security->repository->validatePassword($loginEntity->id, $password);
                } catch (UnprocessableEntityHttpException $e) {
                    //TODO не очень хороший пример того, как мы должны менять пароль, но всё-таки.
                    /** @var SecurityEntity $securityEntity */
                    $securityEntity = \App::$domain->account->security->oneById($loginEntity->id);
                    $securityEntity->password_hash = Yii::$app->security->generatePasswordHash($password);
                    \App::$domain->account->security->updateById($securityEntity->id, $securityEntity);
                    try {
                        $securityEntity = \App::$domain->account->security->repository->validatePassword($loginEntity->id, $password);
                    } catch (UnprocessableEntityHttpException $e) {
                        $error = new ErrorCollection();
                        $error->add('ldap', 'Не получается сменить пароль');
                        throw new UnprocessableEntityHttpException($error);
                    }

                }
                $ip = ArrayHelper::getValue($request, 'ip');
                $securityEntity->token = \App::$domain->account->token->forge($loginEntity->id, $ip);
                $loginEntity->security = $securityEntity;
                return $loginEntity;
            }

        } else {
            $prepareDataHandler = new PrepareData();
            $this->setNextHandler($prepareDataHandler);
            $this->nextHandle($request);
        }
    }

}
