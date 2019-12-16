<?php

/* @var $this yii\web\View
 * @var $entity \domain\news\v1\entities\NewsEntity
 */

use yubundle\storage\domain\v1\entities\StorageEntity;
use yii\helpers\Html;
use yii2rails\extension\enum\enums\ByteEnum;
use yii2rails\extension\widget\entityActions\actions\DeleteAction;
use yii2rails\extension\widget\entityActions\actions\UpdateAction;
use yii2rails\extension\widget\entityActions\EntityActionsWidget;
use yii2rails\extension\yii\widgets\DetailView;
use yubundle\storage\domain\v1\helpers\StorageHelper;
use yubundle\storage\domain\v1\entities\FileEntity;

//$this->title = $entity->title;
//d($entity);
?>

<div class="pull-right">
	<?= EntityActionsWidget::widget([
		'id' => $entity->id,
		'baseUrl' => 'storage/storage',
		'actions' => ['delete'],
		'actionsDefinition' => [
			'update' => UpdateAction::class,
			'delete' => DeleteAction::class,
		],
	]) ?>
</div>

<div>
	<?= DetailView::widget([
	    'labelWidth' => '200px',
		'model' => $entity,
		'attributes' => [
			[
				'attribute' => 'id',
				'label' => ['main', 'id'],
			],
            [
                'attribute' => 'name',
                'label' => Yii::t('main', 'name'),
            ],
            [
                'attribute' => 'extension',
                'label' => Yii::t('main', 'extension'),
            ],
            [
                'attribute' => 'directory',
                'label' => Yii::t('main', 'directory'),
            ],
            [
                'attribute' => 'hash',
                'label' => Yii::t('main', 'hash'),
            ],
            [
                'label' => Yii::t('main', 'size'),
                'value' => function (FileEntity $fileEntity) {
                    return round($fileEntity->size / ByteEnum::KB, 2) . ' KB';
                },
            ],
            [
                'label' => Yii::t('storage/file', 'url'),
                'format' => 'html',
                'value' => function (FileEntity $fileEntity) {
                    return Html::a($fileEntity->hash . DOT . $fileEntity->extension, $fileEntity->url->constant, ['target' => '_blank']);
                },
            ],
            [
                'label' => Yii::t('storage/file', 'source_url'),
                'format' => 'html',
                'value' => function (FileEntity $fileEntity) {
                    return Html::a($fileEntity->file_name, $fileEntity->url->source, ['target' => '_blank']);
                },
            ],
            [
                'label' => Yii::t('storage/file', 'download_url'),
                'format' => 'html',
                'value' => function (FileEntity $fileEntity) {
                    return Html::a(Yii::t('storage/file', 'download_url'), $fileEntity->url->download, ['target' => '_blank']);
                },
            ],
            [
                'label' => Yii::t('storage/file', 'thumb_urls'),
                'format' => 'html',
                'value' => function (FileEntity $fileEntity) {
	                $code = \yii\helpers\ArrayHelper::getValue($fileEntity, 'ext.type.code');
	                if($code != 'image') {
	                    return null;
                    }
                    $html = '<ul>';
	                foreach ($fileEntity->service->thumbs as $thumbEntity) {
	                    $dirName = StorageHelper::forgeThumbDirName($thumbEntity);
	                    $url = str_replace($fileEntity->hash, $dirName . SL . $fileEntity->hash, $fileEntity->url->constant);
                        $html .= '<li>';
                        $html .= Html::a($thumbEntity->width . ' x ' . $thumbEntity->height, $url, ['target' => '_blank']);
                        $html .= '</li>';
	                }
                    $html .= '</ul>';
                    return $html;
                },
            ],

		],
	]) ?>
	
</div>
