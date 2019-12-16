<?php

namespace yubundle\account\domain\v2\interfaces\entities;

use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii2rails\domain\interfaces\repositories\CrudInterface;
use yubundle\account\domain\v2\entities\LoginEntity;

/**
 * Interface LoginEntityInterface
 *
 * @package yubundle\account\domain\v2\interfaces\entities
 *
 * @property integer          $id
 * @property string           $login
 * @property integer          $status
 * @property string           $token
 * @property array            $roles
 * @property string           $username
 * @property string           $created_at
 * @property SecurityEntity   $security
 */
interface LoginEntityInterface extends IdentityInterface {
	


}