<?php

namespace yubundle\staff\api\v1\controllers;

use common\enums\rbac\PermissionEnum;
use yii2lab\rest\domain\rest\ActiveControllerWithQuery as Controller;
use yii2rails\extension\web\helpers\Behavior;
use yubundle\staff\domain\v1\enums\StaffPermissionEnum;

class WorkerController extends Controller
{

	public $service = 'staff.worker';

	public function behaviors()
    {
        return [
            Behavior::cors(),
            Behavior::auth(),
            Behavior::access(StaffPermissionEnum::MANAGE, ['create', 'update', 'delete']),
        ];
    }

}