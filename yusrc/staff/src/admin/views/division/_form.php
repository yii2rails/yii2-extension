<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii2rails\extension\yii\helpers\ArrayHelper;

$companyId = \App::$domain->account->auth->identity->company_id;

//TODO:выпилить костыли
$path = \Yii::$app->request->getUrl();
$path = explode('/', $path);
$path = end($path);
$path = explode('?', $path);

if ($path[0] == 'update') {
    $this->title = Yii::t('staff/division', 'update_division');
    $buttonMessage = Yii::t('action', 'update');
} else {
    $this->title = Yii::t('staff/division', 'new_division');
    $buttonMessage = Yii::t('action', 'create');
}

\App::$domain->navigation->breadcrumbs->create($this->title);
$divisionCollection = \App::$domain->staff->division->all();
$items = ['' => ''];
foreach ($divisionCollection as $division) {
    $items[$division->id] = $division->name;
}

?>
<div class="send-email">

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'company_id')->hiddenInput(['value' => $companyId]) ?>

            <?= $form->field($model, 'parent_id')->dropDownList($items, [Yii::$app->request->getQueryParam('parent_id')]); ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton($buttonMessage, ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
