<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii2rails\extension\yii\helpers\ArrayHelper;

$this->title = Yii::t('staff/company', 'new_company');
\App::$domain->navigation->breadcrumbs->create($this->title);

?>
<div class="send-email">

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'code')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('action', 'create'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
