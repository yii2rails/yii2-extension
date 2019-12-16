<?php

use yubundle\storage\domain\v1\entities\FileEntity;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii2rails\extension\enum\enums\ByteEnum;

/* @var $this yii\web\View
 * @var $dataProvider ArrayDataProvider
 * @var $searchModel \yii\base\Model
 */

$this->title = Yii::t('storage/storage', 'title');
//$searchModel = new \backend\modules\news\forms\search\NewsSearchForm;

?>

<div class="box box-primary">
	<div class="box-body">

        <?= \yii2bundle\navigation\domain\widgets\Alert::widget() ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'layout' => '{summary}{items}{pager}',
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => Yii::t('main', 'id'),
                    'format' => 'html',
                    'value' => function (FileEntity $storageEntity) {
                        return Html::a($storageEntity->file_name, ['/storage/storage/view', 'id' => $storageEntity->id]);
                    },
                ],
                [
                    'attribute' => 'size',
                    'label' => Yii::t('main', 'size'),
                    'value' => function (FileEntity $storageEntity) {
                        return round($storageEntity->size / ByteEnum::KB, 2) . ' KB';
                    },
                ],
                [
                    'attribute' => 'url',
                    'label' => Yii::t('main', 'url'),
                    'format' => 'html',
                    'value' => function (FileEntity $storageEntity) {
                        return Html::a($storageEntity->getFileName(), $storageEntity->url->constant, ['target' => '_blank']);
                    },
                ],
            ],
        ]); ?>
	</div>
</div>

<?= Html::a(Yii::t('action', 'create'), '/storage/storage/create', ['class' => 'btn btn-success']) ?>