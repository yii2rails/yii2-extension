<?php

namespace yubundle\account\domain\v2\services;

use App;
use Yii;
use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\data\Query;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\forms\registration\PersonInfoForm;
use yii2rails\extension\common\enums\StatusEnum;
use yii\web\NotFoundHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yubundle\account\domain\v2\interfaces\services\LoginInterface;
use yubundle\user\domain\v1\entities\ClientEntity;
use yubundle\user\domain\v1\entities\PersonEntity;
use yii2rails\domain\services\base\BaseActiveService;
use yii2rails\extension\common\helpers\InstanceHelper;
use yubundle\account\domain\v2\filters\login\LoginValidator;
use yubundle\account\domain\v2\interfaces\LoginValidatorInterface;

/**
 * Class LoginService
 *
 * @package yubundle\account\domain\v2\services
 *
 * @property \yubundle\account\domain\v2\interfaces\repositories\LoginInterface $repository
 * @property \yubundle\account\domain\v2\Domain $domain
 */
class LoginService extends BaseActiveService implements LoginInterface {
	
	public $relations = [];
	public $prefixList = [];
	public $defaultRole;
	public $defaultStatus;
	public $forbiddenStatusList = [StatusEnum::DISABLE];
	
	/** @var LoginValidatorInterface|array|string $validator */
	public $loginValidator = LoginValidator::class;
	
	public function oneByPhone(string $phone, Query $query = null) {
		return $this->repository->oneByPhone($phone, $query);
	}
	
	public function createWeb(PersonInfoForm $model) {
		$model->scenario = PersonInfoForm::SCENARIO_CREATE_ACCOUNT;
		if(!$model->validate()) {
			throw new UnprocessableEntityHttpException($model);
		}

		if(App::$domain->user->person->isExistsByPhone($model->phone)) {
			$model->addError('phone', Yii::t('account/registration', 'user_already_exists_and_activated'));
			throw new UnprocessableEntityHttpException($model);
		}

        if(App::$domain->account->login->isExistsByLogin($model->login)) {
			$model->addError('login', Yii::t('account/registration', 'user_already_exists_and_activated'));
			throw new UnprocessableEntityHttpException($model);
		}
		
        /** @var PersonEntity $personEntity */
		$data = $model->toArray();
		if(\App::$domain->account->auth->isGuest()) {
            $data['company_id'] = EnvService::get('account.login.defaultCompanyId');
        } else {
            $data['company_id'] = \App::$domain->account->auth->identity->company_id;
        }

		$personEntity = $this->createPerson($data);
		$this->createClient($personEntity);
		$loginEntity = $this->createUser($data, $personEntity);
		return $loginEntity;
	}
	
	private function createPerson(array $data) : PersonEntity {
		$data['birthday'] = $data['birthday_year'] . '-' . $data['birthday_month'] . '-' . $data['birthday_day'];
		$personEntity = App::$domain->user->person->create($data);
		return $personEntity;
	}
	
	private function createClient(PersonEntity $personEntity) : ClientEntity {
		$clientEntity = new ClientEntity;
		$clientEntity->person_id = $personEntity->id;
		$clientEntity->status = StatusEnum::ENABLE;
        \App::$domain->user->repositories->client->insert($clientEntity);
		return $clientEntity;
	}

	private function createUser(array $data, PersonEntity $personEntity) : LoginEntity {
		/** @var LoginEntity $loginEntity */
		$data['person_id'] = $personEntity->id;
		$loginEntity = $this->domain->factory->entity->create($this->id, $data);
		$loginEntity->company_id = ArrayHelper::getValue($data, 'company_id');

        if(App::$domain->has('settings')) {
            $loginEntity->status = App::$domain->settings->system->oneById('account.registration.defaultStatus');
        }

		$this->repository->insert($loginEntity);
        if(App::$domain->has('mail')) {
	        \App::$domain->mail->box->forgeBox($personEntity->id);
        }
		return $loginEntity;
	}
	
	public function oneById($id, Query $query = null) {
		try {
		    /** @var LoginEntity $loginEntity */
			$loginEntity = parent::oneById($id, $query);
		} catch(NotFoundHttpException $e) {
			if($this->domain->oauth->isEnabled()) {
				$loginEntity = \App::$domain->account->oauth->oneById($id);
			} else {
				throw $e;
			}
		}
        if ($this->isForbiddenByStatus($loginEntity->status)) {
		    throw new NotFoundHttpException(Yii::t('account/login', 'status_disabled_message'));
        }
		return $loginEntity;
	}
	
	public function isExistsByLogin($login) {
		return $this->repository->isExistsByLogin($login);
	}
	
	/**
	 * @param $login
	 *
	 * @return \yubundle\account\domain\v2\entities\LoginEntity
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLogin($login, Query $query = null) : LoginEntity {
		return $this->repository->oneByLogin($login, $query);
	}

    public function oneByPersonId(int $personId, Query $query = null) : LoginEntity {
        $query = Query::forge($query);
        $query->andWhere(['person_id' => $personId]);
        return $this->repository->one($query);
    }

	public function isValidLogin($login) {
		return $this->getLoginValidator()->isValid($login);
	}
	
	public function normalizeLogin($login) {
		return $this->getLoginValidator()->normalize($login);
	}
	
	public function isForbiddenByStatus($status) {
		if(empty($this->forbiddenStatusList)) {
			return false;
		}
		return in_array($status, $this->forbiddenStatusList);
	}
	
	/**
	 * @return LoginValidatorInterface
	 */
	private function getLoginValidator() {
		$this->loginValidator = InstanceHelper::ensure($this->loginValidator, [], LoginValidatorInterface::class);
		return $this->loginValidator;
	}
	
}
