<?php

namespace yubundle\staff\api\v1\controllers;

use yubundle\staff\domain\v1\interfaces\services\DivisionInterface;
use yii2lab\rest\domain\rest\ActiveControllerWithQuery as Controller;
use yii2rails\extension\web\helpers\Behavior;

/**
 * Class DivisionController
 * @package yubundle\staff\api\v1\controllers
 *
 * @property-read DivisionInterface $service
 */
class DivisionController extends Controller
{

	public $service = 'staff.division';

	public function behaviors()
    {
        return [
            Behavior::cors(),
            Behavior::auth(),
        ];
    }

    public function actionTree()
    {
        return $this->service->tree();
    }

}