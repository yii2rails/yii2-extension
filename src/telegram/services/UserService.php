<?php

namespace yii2rails\extension\telegram\services;

use yii2rails\extension\telegram\entities\UserEntity;
use yii2rails\extension\telegram\interfaces\services\UserInterface;
use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class UserService
 * 
 * @package yii2rails\extension\telegram\services
 * 
 * @property-read \yii2rails\extension\telegram\Domain $domain
 * @property-read \yii2rails\extension\telegram\interfaces\repositories\UserInterface $repository
 */
class UserService extends BaseActiveService implements UserInterface {

    public function updateState($userId, $botId, $state) {
        $query = new Query;
        $query->andWhere([
            'user_id' => $userId,
            'bot_id' => $botId,
        ]);
        $userEntity = $this->one($query);
        $userEntity->state = $state;
        $this->repository->update($userEntity);
    }

    public function oneByFrom($from, $botId) {
        try {
            $query = new Query;
            $query->andWhere([
                'user_id' => $from->id,
                'bot_id' => $botId,
            ]);
            $userEntity = $this->one($query);
        } catch (NotFoundHttpException $e) {
            $userEntity = new UserEntity;
            $userEntity->user_id = $from->id;
            $userEntity->bot_id = $botId;
            $userEntity->username = $from->username;
            $userEntity->first_name = $from->first_name;
            $userEntity->is_bot = $from->is_bot;
            $this->repository->insert($userEntity);
        }
        return $userEntity;
    }

}
