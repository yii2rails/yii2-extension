<?php

namespace yii2rails\extension\jwt\services;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\extension\jwt\interfaces\services\ProfileInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ProfileService
 * 
 * @package yii2rails\extension\jwt\services
 * 
 * @property-read \yii2rails\extension\jwt\Domain $domain
 * @property-read \yii2rails\extension\jwt\interfaces\repositories\ProfileInterface $repository
 */
class ProfileService extends BaseActiveService implements ProfileInterface {

    public function oneById($id, Query $query = null) {
        try {
            $profileEntity = $this->repository->oneById($id);
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException("Profile \"{$id}\" not defined!", 0, $e);
        }
        $profileEntity->validate();
        return $profileEntity;
    }

}
