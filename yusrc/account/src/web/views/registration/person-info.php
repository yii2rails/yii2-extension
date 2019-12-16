<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii\base\Model */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('user/registration', 'title');
?>
<div class="user-signup">

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
                    <?= $form->field($model, 'first_name')->textInput(['placeholder' => $model->getAttributeLabel('first_name')]); ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'last_name')->textInput(['placeholder' => $model->getAttributeLabel('last_name')]); ?>
                </div>
            </div>

            <?= $form->field($model, 'login')->textInput(['placeholder' => $model->getAttributeLabel('login')]); ?>

            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'birthday_day')->textInput(['placeholder' => $model->getAttributeLabel('birthday_day')]); ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'birthday_month')->textInput(['placeholder' => $model->getAttributeLabel('birthday_month')]); ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'birthday_year')->textInput(['placeholder' => $model->getAttributeLabel('birthday_year')]); ?>
                </div>
            </div>

            <?= $form->field($model, 'phone')->textInput(['placeholder' => $model->getAttributeLabel('phone')]); ?>

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
