<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii2lab\notify\admin\forms\SmsForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii2rails\extension\yii\helpers\ArrayHelper;


//TODO:выпилить костыли
$path = \Yii::$app->request->getUrl();
$path = explode('/', $path);
$path = end($path);
$path = explode('?', $path);

if ($path[0] == 'update') {
    $titleMessage = Yii::t('reference/main', 'update_item');
    $buttonMessage = Yii::t('action', 'update');
} else {
    $titleMessage = Yii::t('reference/main', 'new_item');
    $buttonMessage = Yii::t('action', 'create');
}

$this->title = $titleMessage;

/** TODO: Изменить заголовок страницы */

\App::$domain->navigation->breadcrumbs->create($this->title);

?>
<div class="send-email">

    <div class="row">
        <div class="col-lg-5">
			<?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'reference_book_id')->hiddenInput(['value' => Yii::$app->request->getQueryParam('reference_book_id')]) ?>

            <?= $form->field($model, 'parent_id')->hiddenInput(['value' => Yii::$app->request->getQueryParam('parent_id')]) ?>
			
			<?= $form->field($model, 'code')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'value')->textInput() ?>

            <?= $form->field($model, 'short_value')->textInput() ?>

			<?= $form->field($model, 'entity')->textInput() ?>

            <div class="form-group">
				<?= Html::submitButton($buttonMessage, ['class' => 'btn btn-primary']) ?>
            </div>
			
			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
