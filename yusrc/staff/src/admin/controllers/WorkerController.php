<?php

namespace yubundle\staff\admin\controllers;

use App;
use domain\contact\v1\entities\PersonalEntity;
use http\Url;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii2bundle\navigation\domain\widgets\Alert;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\web\helpers\ControllerHelper;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\account\domain\v2\forms\registration\PersonInfoForm;
use yubundle\account\domain\v2\forms\restorePassword\UpdatePasswordForm;
use yubundle\account\domain\v2\helpers\LDAPHelper;
use yubundle\staff\admin\forms\WorkerForm;
use yii2rails\domain\web\ActiveController as Controller;
use yubundle\staff\domain\v1\entities\WorkerEntity;
use yubundle\user\domain\v1\entities\PersonEntity;
use Zxing\NotFoundException;

/**
 * Class WorkerController
 *
 * @property \yii2bundle\account\domain\v3\Domain $domain
 */
class WorkerController extends Controller
{

    const RENDER_INDEX = '@yubundle/staff/admin/views/worker/index';
    const RENDER_VIEW = '@yubundle/staff/admin/views/worker/view';

    public $titleName = 'full_name';

    public $formClass = WorkerForm::class;

    public $service = 'staff.worker';

    public function actions()
    {
        $actions = parent::actions();
//        $actions['view']['render'] = self::RENDER_VIEW;
        $actions['index']['render'] = self::RENDER_INDEX;
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['view']);
        return $actions;
    }

    public function actionLoadData()
    {
        //TODO: продумать, как сделать иначе
        LDAPHelper::loadUserdata();
        $baseUrl = ControllerHelper::getUrl();
        return $this->redirect($baseUrl . '?expand=division,post,person');
    }

    public function actionCreate()
    {
        $model = new WorkerForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            try {
                $result = $this->create($model->toArray());
//                d($result);
                return $this->redirect('/staff/worker/view?id='.$result->id);
            } catch(UnprocessableEntityHttpException $e) {
                $error = Json::decode($e->getMessage())[0];
                $model->addError('errors', $error['message']);
                if ($error['field']=='phone') {
                    $model->addError('outer_phone', $error['message']);
                }
                if ($error['field']=='birthday_day') {
                    $model->addError('birthday', "Необходимо заполнить ДР в формате гггг-мм-дд");
                }
                if ($error['field']=='login') {
                    $model->addError('user_login', $error['message']);
                }
                if ($error['field']=='password') {
                    $model->addError('user_password', $error['message']);
                }
                if ($error['field']=='password_confirm') {
                    $model->addError('accept_password', $error['message']);
                }
                $model->addErrors($e->getErrorsForModel());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = new WorkerForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            try {
                $this->updateById($id, $model->toArray());


                return $this->redirect('/staff/worker/view?id='.$id);
            } catch(NotFoundHttpException $e) {
                $model->addError('phone', $e->getMessage());
                $model->addErrors($e->getErrorsForModel());
            }
            catch (UnprocessableEntityHttpException $e) {
                $error = Json::decode($e->getMessage())[0];
                if ($error['field']=='phone') {
                    $model->addError('outer_phone', $error['message']);
                }
                if ($error['field']=='corporate_phone') {
                    $model->addError('private_phone', $error['message']);
                }
                $model->addErrors($e->getErrorsForModel());
            }
        } else {
            $model = $this->oneById($id);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $entity = $this->oneById($id);
        return $this->render('view', [
            'entity' => $entity,
        ]);
    }

    private function updateById($id, $data)
    {
        $this->workerFormValidate($data, WorkerForm::SCENARIO_UPDATE_WORKER);

        $query = new Query;
        /** @var \yubundle\staff\domain\v1\entities\WorkerEntity $workerEntity */
        $workerEntity = \App::$domain->staff->worker->oneById($id, $query);
        $workerEntity->division_id = $data['division'];
        $workerEntity->post_id = $data['post'];
        $workerEntity->office = $data['office'];
        $workerEntity->corporate_phone = $data['private_phone'];
        $workerEntity->phone = $workerEntity->corporate_phone;

        $query = new Query;
        /** @var \yubundle\user\domain\v1\entities\PersonEntity $personEntity */
        $personEntity = \App::$domain->user->person->oneById($workerEntity->person_id, $query);
        $personEntity->first_name = $data['first_name'];
        $personEntity->last_name = $data['last_name'];
        $personEntity->middle_name = $data['middle_name'];
        $personEntity->code = $data['code'];
        $personEntity->sex_id = $data['sex'];
        $personEntity->birthday = $data['birth_date'];
        $personEntity->email = $data['outer_email'];
        $personEntity->phone = $data['outer_phone'];

        /** @var  \yubundle\account\domain\v2\entities\LoginEntity $userEntity */
        $query = Query::forge();
        $query->where(['person_id' => $workerEntity->person_id]);
        $userEntity = \App::$domain->account->login->one($query);


        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {

            \App::$domain->staff->worker->update($workerEntity);
            \App::$domain->user->person->update($personEntity);
            \App::$domain->account->login->update($userEntity);

            $transaction->commit();
        } catch (Exception $e) {
            var_dump($e);
            $transaction->rollback();
        }
    }


    private function oneById($id, Query $query = null)
    {
        $workerForm = new WorkerForm;

        /** @var \yubundle\staff\domain\v1\entities\WorkerEntity $workerEntity */
        $query = new Query;
        $query->with('person.sex');
        $query->with('post');
        $query->with('division');
        $query->with('user');
        $workerEntity = \App::$domain->staff->worker->oneById($id, $query);

        $workerForm->entity = $workerEntity;

        $workerForm->first_name = $workerEntity->person->first_name;
        $workerForm->last_name = $workerEntity->person->last_name;
        $workerForm->middle_name = $workerEntity->person->middle_name;
        $workerForm->private_phone = $workerEntity->phone;
        $workerForm->code = $workerEntity->person->code;
        $workerForm->sex = $workerEntity->person->sex_id;

        $workerForm->post = $workerEntity->post_id;
        $workerForm->division = $workerEntity->division_id;
        $workerForm->birth_date = $workerEntity->person->birthday;
        $workerForm->outer_email = $workerEntity->person->email;
        $workerForm->outer_phone = $workerEntity->person->phone;
        $workerForm->office = $workerEntity->office;

        if ($workerEntity->user !== null) {
            $workerForm->user_login = $workerEntity->user->login;
        }

        if (Yii::$app->controller->route == 'staff/worker/view') {
            return $workerForm->entity;
        }

        return $workerForm;
    }

    private function create($data)
    {
        $this->workerFormValidate($data);

        // вот на этом шаге, все валидации уже успешно прошли, и мы можем заполнять данные
        $model = new PersonInfoForm();

        $model->birth_date = $data['birth_date'];

        list($year, $month, $day) = explode('-', $data['birth_date']);
        $model->birthday_day = $day;
        $model->birthday_month = $month;
        $model->birthday_year = $year;

        $data['company_id'] = \App::$domain->account->auth->identity->company_id;

        $model->login = $data['user_login'];
        $model->password = $data['user_password'];
        $model->password_confirm = $data['accept_password'];

        $model->first_name = $data['first_name'];
        $model->last_name = $data['last_name'];
        $model->middle_name = $data['middle_name'];

        $model->phone = $data['outer_phone'];
        $model->company_id = $data['company_id'];


        // разом либо создавается всё либо не создавается
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {

            $loginEntity = \App::$domain->account->login->createWeb($model);

            $person_id = $loginEntity->person_id;

            $query = new Query();
            $query->andWhere(['company_id' => $data['company_id']]);
            $domain = \App::$domain->mail->companyDomain->one($query);

            $workerEntity = new WorkerEntity();

            $workerEntity->person_id = $loginEntity->person_id;
            $workerEntity->company_id = $data['company_id'];
            $workerEntity->email = $data['user_login'] . '@' . $domain->domain;
            $workerEntity->division_id = $data['division'];
            $workerEntity->post_id =  $data['post'];
            $workerEntity->office = $data['office'];
            $workerEntity->phone = $data['private_phone'];

            try {
                $query = new Query();
                $query->where(['person_id' => $person_id]);
                $workerResult = \App::$domain->staff->worker->one($query);
//                d($workerResult);
            } catch (NotFoundHttpException $e) {

                $workerResult = \App::$domain->staff->repositories->worker->insert($workerEntity);
//                d($workerResult);
            }

            $transaction->commit();
        } catch (Exception $e) {
            var_dump($e);
            $transaction->rollback();
        }


        return $workerResult;
    }


    private function workerFormValidate($data,$type = WorkerForm::SCENARIO_CREATE_WORKER) {

        $workerFormModel = new WorkerForm();

        $workerFormModel->first_name = $data['first_name'];
        $workerFormModel->last_name = $data['last_name'];
        $workerFormModel->middle_name = $data['middle_name'];

        $workerFormModel->birth_date = $data['birth_date'];
        if (isset($data['birth_date']) && $data['birth_date'] !== '') {
            try {
                list($year, $month, $day) = explode('-', $data['birth_date']);
                $workerFormModel->birthday_day = $day;
                $workerFormModel->birthday_month = $month;
                $workerFormModel->birthday_year = $year;
                if ($workerFormModel->birthday_year < 1800) {
                    $error = new ErrorCollection;
                    $error->add('birth_date', 'staff/worker', 'year');
                    throw new UnprocessableEntityHttpException($error);
                }
            } catch (\ErrorException $exception) {
//                $workerFormModel->validate();
            }
        }

        $workerFormModel->code = $data['code'];
        $workerFormModel->sex = $data['sex'];
        $workerFormModel->outer_phone = $data['outer_phone'];
        $workerFormModel->division = $data['division'];
        $workerFormModel->post =  $data['post'];
        $workerFormModel->office = $data['office'];
        $workerFormModel->private_phone = $data['private_phone'];

        $workerFormModel->user_login = $data['user_login'];
        $workerFormModel->user_password = $data['user_password'];
        $workerFormModel->accept_password    = $data['accept_password'];

        $workerFormModel->scenario = $type;
        if ($type == WorkerForm::SCENARIO_UPDATE_WORKER) {

//            $isExistsPhone = App::$domain->account->contact->isExistsByData($workerFormModel->phone, 'phone');
//            if($isExistsPhone) {
//                $workerFormModel->addError('outer_phone', Yii::t('account/registration', 'user_already_exists_and_activated'));
//                throw new UnprocessableEntityHttpException($workerFormModel);
//            }
//
//            if(App::$domain->account->login->isExistsByLogin($workerFormModel->login)) {
//                $workerFormModel->addError('user_login', Yii::t('account/registration', 'user_already_exists_and_activated'));
//                throw new UnprocessableEntityHttpException($workerFormModel);
//            }
        }

        if(!$workerFormModel->validate()) {
            throw new UnprocessableEntityHttpException($workerFormModel);
        }
    }
}
