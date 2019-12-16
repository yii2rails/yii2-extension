<?php

/* @var $this yii\web\View
 * @var $entity \yubundle\reference\domain\entities\ItemEntity
 */

use yii2rails\extension\yii\widgets\DetailView;
use yii2rails\extension\yii\helpers\Html;


$this->title = $entity->value;

?>

<div>

	<?= DetailView::widget([
	    'labelWidth' => '200px',
		'model' => $entity,
		'attributes' => [
            [
                'attribute' => 'code',
                'label' => Yii::t('reference/main', 'code'),
            ],
            [
                'attribute' => 'value',
                'label' => Yii::t('reference/main', 'value'),
            ],
            [
                'attribute' => 'short_value',
                'label' => Yii::t('reference/main', 'short_value'),
            ],
            [
                'attribute' => 'entity',
                'label' => Yii::t('reference/main', 'entity'),
            ],
            [
                'label' => Yii::t('reference/main', 'status'),
                'format' => 'html',
                'value' => function ($entity) {
                    return Html::renderBoolean($entity->status);
                },
            ],
		],
	])?>

</div>
