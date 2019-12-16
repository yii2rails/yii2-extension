<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii2lab\notify\admin\forms\SmsForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii2rails\extension\yii\helpers\ArrayHelper;

$this->title = Yii::t('reference/main', 'new_reference');
\App::$domain->navigation->breadcrumbs->create($this->title);

$companyCollection = App::$domain->staff->company->all();

?>
<div class="send-email">

    <div class="row">
        <div class="col-lg-5">
			<?php $form = ActiveForm::begin(); ?>
			
			<?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'levels')->textInput() ?>

			<?= $form->field($model, 'entity')->textInput() ?>

            <?= $form->field($model, 'owner_id')->dropDownList(ArrayHelper::map($companyCollection, 'id', 'name')) ?>

            <div class="form-group">
				<?= Html::submitButton(Yii::t('action', 'create'), ['class' => 'btn btn-primary']) ?>
            </div>
			
			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
