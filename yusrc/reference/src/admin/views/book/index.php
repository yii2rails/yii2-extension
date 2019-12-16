<?php

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\BaseDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii2rails\extension\web\helpers\ControllerHelper;

$this->title = Yii::t('reference/main', 'title');

$baseUrl = ControllerHelper::getUrl();

$columns = [
    [
        'label' => Yii::t('reference/main', 'name'),
        'format' => 'raw',
        'value' => function(\yubundle\reference\domain\entities\BookEntity $data) {
            return
                Html::a(
                    $data->name,
                    ['/reference/item/index', 'reference_book_id' => $data->id, 'parent_id' => '{null}']
                );
        },
    ],
    [
        'attribute' => 'entity',
        'label' => Yii::t('reference/main', 'entity'),
    ],
    [
        'attribute' => 'levels',
        'label' => Yii::t('reference/main', 'levels'),
    ],
];

?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{summary}{items}',
	'columns' => $columns,
]); ?>

<?= Html::a(Yii::t('action', 'create'), $baseUrl . 'create', ['class' => 'btn btn-success']) ?>
