<?php

/* @var $this yii\web\View
 * @var $entity \yubundle\staff\domain\v1\entities\WorkerEntity
 */

use yii2rails\extension\web\helpers\ControllerHelper;
use yii2rails\extension\yii\widgets\DetailView;
use yii2rails\extension\yii\helpers\Html;
use yubundle\staff\domain\v1\entities\WorkerEntity;

$this->title = $entity->full_name;
$baseUrl = ControllerHelper::getUrl();

?>

<?= DetailView::widget([
    'labelWidth' => '200px',
    'model' => $entity,
    'attributes' => [
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'full_name'),
            'value' => function (WorkerEntity $entity) {
                return $entity->getFullName();
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'phone'),
            'value' => function (WorkerEntity $entity) {
                return $entity->phone;
            },
        ],

        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'email'),
            'value' => function (WorkerEntity $entity) {
                return $entity->email;
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'code'),
            'value' => function (WorkerEntity $entity) {
                return $entity->person->code;
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'sex'),
            'value' => function (WorkerEntity $entity) {
                if ($entity->person->sex)
                return $entity->person->sex->value;
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'post'),
            'value' => function (WorkerEntity $entity) {
                return $entity->post->value;
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'division'),
            'value' => function (WorkerEntity $entity) {
                if ($entity->division)
                return $entity->division->name;
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'birthday'),
            'value' => function (WorkerEntity $entity) {
                return $entity->person->birthday;
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'outer_email'),
            'value' => function (WorkerEntity $entity) {
                return $entity->person->email;
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'outer_phone'),
            'value' => function (WorkerEntity $entity) {
                return $entity->person->phone;
            },
        ],
        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'office'),
            'value' => function (WorkerEntity $entity) {
                return $entity->office;
            },
        ],

        [
            'format' => 'html',
            'label' => Yii::t('staff/worker', 'login'),
            'value' => function (WorkerEntity $entity) {
                if ($entity->user)
                return $entity->user->login;
            },
        ],

    ],
])?>

<?= \yii\helpers\Html::a(Yii::t('staff/worker', 'update'), $baseUrl . 'update?id='.$entity->id, ['class' => 'btn btn-success']) ?>

<div>



</div>
