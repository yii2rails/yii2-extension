<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii\base\Model */
/* @var $person array */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('user/registration', 'verify_activation_code');
?>
<div class="user-signup">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('user/registration', 'activation_code_be_sent {phone}', $person) ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'fieldConfig' => [
                    'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                ],
            ]); ?>
			
			<?= $form->field($model, 'activation_code')->textInput(['placeholder' => $model->getAttributeLabel('activation_code')]); ?>

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
