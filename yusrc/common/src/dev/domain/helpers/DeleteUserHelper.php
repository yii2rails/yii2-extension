<?php

namespace yubundle\common\dev\domain\helpers;

use Yii;
use App;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\entities\ConfirmEntity;

class DeleteUserHelper
{

    public static function delete($phone)
    {
        self::deleteConfirm($phone);
        $personId = self::onePerson($phone);
        self::deleteClient($personId);
        self::deleteWorker($personId);
        DeleteUserMailHelper::delete($personId);
        self::deleteIdentity($personId);
        self::deletePerson($personId);
    }

    private static function onePerson($phone)
    {
        try {
            $personEntity = App::$domain->user->person->oneByPhone($phone);
            return $personEntity->id;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Персона не найдена!');
        }
    }

    private static function deleteConfirm($phone)
    {
        $query = new Query;
        $query->andWhere(['login' => $phone]);
        $confirmEntityCollection = App::$domain->account->confirm->all($query);
        /** @var ConfirmEntity $confirmEntity */
        foreach ($confirmEntityCollection as $confirmEntity) {
            App::$domain->account->repositories->confirm->deleteByLoginAndAction($confirmEntity->login, $confirmEntity->action);
        }
    }

    private static function deletePerson($personId)
    {
        App::$domain->user->person->deleteById($personId);
    }

    private static function deleteIdentity($personId)
    {
        try {
            $identityEntity = App::$domain->account->login->oneByPersonId($personId);
            App::$domain->account->login->deleteById($identityEntity->id);
        } catch (NotFoundHttpException $e) {
        }
    }

    private static function deleteWorker($personId)
    {
        if (!App::$domain->has('staff')) {
            return;
        }
        try {
            $workerEntity = App::$domain->staff->worker->oneByPersonId($personId);
            App::$domain->staff->repositories->worker->delete($workerEntity);
        } catch (NotFoundHttpException $e) {
        }
    }

    private static function deleteClient($personId)
    {
        try {
            $clientEntity = App::$domain->user->client->oneByPersonId($personId);
            App::$domain->user->client->deleteById($clientEntity->id);
        } catch (NotFoundHttpException $e) {
        }
    }

}
