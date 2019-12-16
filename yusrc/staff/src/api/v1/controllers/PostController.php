<?php

namespace yubundle\staff\api\v1\controllers;

use yubundle\staff\domain\v1\interfaces\services\DivisionInterface;
use yii2lab\rest\domain\rest\ActiveControllerWithQuery as Controller;
use yii2rails\extension\web\helpers\Behavior;

class PostController extends Controller
{

	public $service = 'staff.post';

	public function behaviors()
    {
        return [
            Behavior::cors(),
            Behavior::auth(),
        ];
    }

}