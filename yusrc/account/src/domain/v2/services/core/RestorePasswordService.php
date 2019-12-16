<?php

namespace yubundle\account\domain\v2\services\core;

use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\core\domain\repositories\base\BaseCoreRepository;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\core\domain\services\base\BaseCoreService;
use yubundle\account\domain\v2\forms\restorePassword\UpdatePasswordForm;
use yubundle\account\domain\v2\forms\RestorePasswordForm;
use yubundle\account\domain\v2\interfaces\services\RestorePasswordInterface;

/**
 * Class RestorePasswordService
 *
 * @package yubundle\account\domain\v2\services\core
 *
 * @property-read BaseCoreRepository $repository
 */
class RestorePasswordService extends BaseCoreService implements RestorePasswordInterface {
	
	public $point = 'restore-password';
    public $version = 1;
	public $tokenExpire;

    public function requestCode(UpdatePasswordForm $model) {
        $model->scenario = UpdatePasswordForm::SCENARIO_REQUEST_CODE;
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
        $response = $this->repository->post('request-activation-code', $model->toArray());
    }

    public function verifyCode(UpdatePasswordForm $model) {
        $model->scenario = UpdatePasswordForm::SCENARIO_VERIFY_CODE;
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
        $this->repository->post('verify-activation-code', $model->toArray());
    }

    public function setNewPassword(UpdatePasswordForm $model) {
        $model->scenario = UpdatePasswordForm::SCENARIO_SET_PASSWORD;
        if(!$model->validate()) {
            throw new UnprocessableEntityHttpException($model);
        }
        $this->repository->post('set-password', $model->toArray());
    }
	
}
