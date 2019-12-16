<?php

use yii\grid\GridView;
use yii2rails\extension\web\helpers\ControllerHelper;
use yii2rails\extension\yii\helpers\Html;
use yubundle\staff\domain\v1\entities\DivisionEntity;
use yii2rails\extension\web\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\BaseDataProvider */

$this->title = Yii::t('staff/division', 'title');

$baseUrl = ControllerHelper::getUrl();

$columns = [
    [
        'format' => 'html',
        'label' => Yii::t('staff/division', 'name'),
        'value' => function(DivisionEntity $entity) {
            return Html::a($entity->name, ['/staff/division/index', 'parent_id' => $entity->id]);
        },
    ],
    [
        'label' => Yii::t('staff/division', 'status'),
        'format' => 'html',
        'value' => function ($entity) {
            return Html::renderBoolean($entity->status);
        },
    ],
    [
        'class' => ActionColumn::class,
        'template' => '{update} {delete}'
    ]
];

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{summary}{items}{pager}',
    'columns' => $columns,
]); ?>

<?= Html::a(Yii::t('action', 'create'), [
    $baseUrl . 'create',
    'parent_id' => Yii::$app->request->getQueryParam('parent_id'),
], ['class' => 'btn btn-success'])?>
