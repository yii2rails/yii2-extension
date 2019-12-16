<?php

namespace yubundle\account\domain\v2\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yubundle\account\domain\v2\interfaces\repositories\ConfirmInterface;
use yubundle\account\domain\v2\models\UserConfirm;

class ConfirmRepository extends BaseActiveArRepository implements ConfirmInterface {
	
	protected $modelClass = 'yubundle\account\domain\v2\models\UserConfirm';
	protected $primaryKey = false;
	
	public function uniqueFields() {
		return [
			['login', 'action'],
		];
	}
	
	public function oneByLoginAndAction($login, $action) {
		$model = $this->oneModelByCondition([
			'login' => $login,
			'action' => $action,
		]);
		return $this->forgeEntity($model);
	}
	
	public function oneByLogin($login) {
		$model = $this->oneModelByCondition(['login' => $login]);
		return $this->forgeEntity($model);
	}

    public function deleteByLoginAndAction($login, $action) {
        /** @var UserConfirm $model */
        $model = $this->model->find()->where([
            'login' => $login,
            'action' => $action,
        ])->one();
        $model->delete();
    }

	public function cleanOld($login, $action) {
		/** @var UserConfirm[] $all */
		$all = $this->model->find()->where([
			'login' => $login,
			'action' => $action,
		])->all();
		foreach($all as $model) {
			if(TIMESTAMP > $model->expire) {
				$model->delete();
			}
		}
	}
	
	public function cleanAll($login, $action) {
		/** @var UserConfirm[] $all */
		$all = $this->model->find()->where([
			'login' => $login,
			'action' => $action,
		])->all();
		foreach($all as $model) {
			$model->delete();
		}
	}
	
}
