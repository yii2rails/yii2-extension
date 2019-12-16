<?php

use yii2rails\extension\web\grid\ActionColumn;
use yubundle\staff\domain\v1\entities\WorkerEntity;
use yii\grid\GridView;
use yii\helpers\Html;
use yii2rails\extension\web\helpers\ControllerHelper;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\BaseDataProvider */



$this->title = Yii::t('staff/worker', 'title');

$baseUrl = ControllerHelper::getUrl();
//d($dataProvider->getModels());

$columns = [
    [
        'format' => 'html',
        'label' => Yii::t('staff/worker', 'full_name'),
        'value' => function (WorkerEntity $entity) {
            return Html::a($entity->full_name, ['/staff/worker/view', 'id' => $entity->id]);
        },
    ],
    [
        'format' => 'html',
        'label' => Yii::t('staff/worker', 'division'),
        'value' => function (WorkerEntity $entity) {
            return Html::a($entity->division_name, ['/staff/worker/view', 'id' => $entity->id]);
        },
    ],
    [
        'format' => 'html',
        'label' => Yii::t('staff/worker', 'post'),
        'value' => function (WorkerEntity $entity) {
            return Html::a($entity->post_name, ['/staff/worker/view', 'id' => $entity->id]);
        },
    ],
    [
        'format' => 'html',
        'label' => Yii::t('staff/worker', 'phone'),
        'value' => function (WorkerEntity $entity) {
            return Html::a($entity->phone, ['/staff/worker/view', 'id' => $entity->id]);
        },
    ],
    [
        'class' => ActionColumn::class,
        'template' => '{update} {delete}'
    ],
];

?>

<?=  GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{summary}{items}{pager}',
    'columns' => $columns,
]);  ?>


<?= Html::a(Yii::t('action', 'create'), $baseUrl . 'create', ['class' => 'btn btn-success']) ?>

<?php Html::a('Загрузить из ldap', $baseUrl . 'load-data', ['class' => 'btn btn-success ml-2', 'style' => 'margin-left: 10px;']) ?>