<?php

namespace yubundle\staff\domain\v1\services;

use App;
use function GuzzleHttp\Promise\queue;
use Yii;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\web\helpers\ControllerHelper;
use yubundle\account\domain\v2\entities\LoginEntity;
use yubundle\staff\domain\v1\behaviors\WorkerQueryBehavior;
use yubundle\staff\domain\v1\behaviors\WorkerSearchBehavior;
use yubundle\staff\domain\v1\entities\WorkerEntity;
use yubundle\staff\domain\v1\interfaces\services\WorkerInterface;
use yii\web\NotFoundHttpException;
use yii2rails\domain\behaviors\query\SearchFilter;
use yii2rails\domain\data\Query;
use yii2rails\domain\services\base\BaseActiveService;
use yubundle\user\domain\v1\entities\PersonEntity;
use yubundle\account\domain\v2\forms\registration\PersonInfoForm;
use yubundle\staff\admin\forms\WorkerForm;
use Zxing\NotFoundException;

/**
 * Class WorkerService
 *
 * @package yubundle\staff\domain\v1\services
 *
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 * @property-read \yubundle\staff\domain\v1\interfaces\repositories\WorkerInterface $repository
 */
class WorkerService extends BaseActiveService implements WorkerInterface
{

    public function behaviors()
    {
        return [
            WorkerQueryBehavior::class,
            WorkerSearchBehavior::class,
        ];
    }

    public function oneByPersonId($personId) : WorkerEntity
    {
        $query = new Query;
        $query->andWhere(['person_id' => $personId]);
        return $this->repository->one($query);
    }

    public function allByPersonIds(array $personIds)
    {
        return $this->repository->allByPersonIds($personIds);
    }

    public function allByPostIds(array $personIds)
    {
        return $this->repository->allByPostIds($personIds);
    }

    public function oneSelf(): WorkerEntity
    {
        $personId = $myMail = \App::$domain->account->auth->identity->person_id;
        $query2 = new Query;
        $query2->with('worker');
        /** @var PersonEntity $personEntity */
        $personEntity = $myMail = \App::$domain->user->person->oneById($personId, $query2);
        if ($personEntity->worker == null) {
            throw new NotFoundHttpException(\Yii::t('staff/worker', 'not_found'));
        }
        return $personEntity->worker;
    }





}
