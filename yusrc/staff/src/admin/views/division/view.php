<?php

/* @var $this yii\web\View
 * @var $entity \yubundle\staff\domain\v1\entities\CompanyEntity
 */

use yii2rails\extension\yii\widgets\DetailView;
use yii2rails\extension\yii\helpers\Html;


$this->title = Yii::t('staff/company', 'view_company') . ' ' . $entity->name;

?>

<div>

	<?=DetailView::widget([
	    'labelWidth' => '200px',
		'model' => $entity,
		'attributes' => [
            [
                'attribute' => 'name',
                'label' => Yii::t('staff/company', 'name'),
            ],
            [
                'label' => Yii::t('staff/company', 'status'),
                'format' => 'html',
                'value' => function ($entity) {
                    return Html::renderBoolean($entity->status);
                },
            ],
		],
	])?>

</div>
