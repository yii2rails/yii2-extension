<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \yubundle\account\web\forms\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<br/>

<?php $form = ActiveForm::begin([
    'id' => 'form-signup',
    'fieldConfig' => [
        'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
    ],
]); ?>

	<?= $form->field($model, 'login')->textInput(['placeholder' => $model->getAttributeLabel('login')]) ?>

	<?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
	
	<?php
        if(1 == 2) {
	        echo $form->field($model, 'rememberMe', [
		        'checkboxTemplate'=>'<div class="checkbox">{beginLabel}{input}{labelTitle}{endLabel}{error}{hint}</div>',
	        ])->checkbox();
        }
    ?>
	
	<div class="form-group">
		<?=Html::submitButton(Yii::t('account/auth', 'login_action'), ['class' => 'btn btn-primary btn-flat', 'name' => 'login-button']) ?>
	</div>
	
<?php ActiveForm::end(); ?>

<?= Html::a(Yii::t('account/auth', 'register_new_user'), ['/user/registration']) ?>
    <br/>
<?= Html::a(Yii::t('account/auth', 'i_forgot_my_password'), ['/user/restore-password']) ?>