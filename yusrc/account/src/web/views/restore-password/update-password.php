<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii\base\Model */
/* @var $person array */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('user/restore-password', 'update-password');
?>
<div class="user-restore-password">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'fieldConfig' => [
                    'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                ],
            ]); ?>

            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]); ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'password_confirm')->passwordInput(['placeholder' => $model->getAttributeLabel('password_confirm')]); ?>
                </div>
            </div>

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
