<?php

use yii\grid\GridView;
use yii2rails\extension\web\helpers\ControllerHelper;
use yii2rails\extension\yii\helpers\Html;
use yubundle\staff\domain\v1\entities\CompanyEntity;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\BaseDataProvider */

$this->title = Yii::t('staff/company', 'title');

$baseUrl = ControllerHelper::getUrl();

$columns = [
    [
        'format' => 'html',
        'label' => Yii::t('staff/company', 'code'),
        'value' => function(CompanyEntity $entity) {
            return Html::a($entity->code, ['/staff/company/view', 'id' => $entity->id]);
        },
    ],
    [
        'format' => 'html',
        'label' => Yii::t('staff/company', 'name'),
        'value' => function(CompanyEntity $entity) {
            return Html::a($entity->name, ['/staff/company/view', 'id' => $entity->id]);
        },
    ],
    [
        'label' => Yii::t('staff/company', 'status'),
        'format' => 'html',
        'value' => function ($entity) {
            return Html::renderBoolean($entity->status);
        },
    ],
];

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{summary}{items}{pager}',
    'columns' => $columns,
]); ?>

<?= Html::a(Yii::t('action', 'create'), $baseUrl . 'create', ['class' => 'btn btn-success']) ?>
