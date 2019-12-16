<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii\base\Model */
/* @var $person array */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('user/restore-password', 'title');
?>
<div class="user-restore-password">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'phone')->textInput(['placeholder' => '+7']); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('action', 'next'), [
                    'class' => 'btn btn-primary',
                    'name' => 'create-button',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
