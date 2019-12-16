<?php

namespace yubundle\common\dev\domain\helpers;

use Yii;
use App;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;

class DeleteUserMailHelper {

    public static function delete($personId) {
        if(!App::$domain->has('mail')) {
            return;
        }
        try {
            $boxEntity = App::$domain->mail->box->oneByPersonId($personId);
            self::deleteDialogs($boxEntity->email);
            self::deleteDiscussions($boxEntity->email);
            self::deleteBox($boxEntity->id);
        } catch (NotFoundHttpException $e) {}
    }

    private static function deleteBox($boxId) {
        App::$domain->mail->box->deleteById($boxId);
    }

    private static function deleteDiscussions($email) {
        $query = new Query;
        $query->andWhere(['email' => $email]);
        $memberCollection = App::$domain->mail->repositories->discussionMember->all($query);
        foreach ($memberCollection as $memberEntity) {
            App::$domain->mail->repositories->discussionMember->delete($memberEntity);
        }
    }

    private static function deleteDialogs($email) {
        $query = new Query;
        $query->andWhere([
            'or',
            ['actor' => $email],
            ['contractor' => $email],
        ]);
        $dialogCollection = App::$domain->mail->repositories->dialog->all($query);
        foreach ($dialogCollection as $dialogEntity) {
            App::$domain->mail->repositories->dialog->delete($dialogEntity);
        }
    }

}
