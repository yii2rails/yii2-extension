<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \domain\news\v1\entities\NewsEntity */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

//$this->title = $model->title;
//\App::$domain->navigation->breadcrumbs->create($this->title);

?>

<div class="row">

    <div class="col-lg-3">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data',  'id' => 'form']]) ?>

        <?= $form->field($model, 'file')->fileInput() ?>

        <div class="form-group">
            <?php
            echo Html::submitButton(Yii::t('action', 'upload'), ['class' => 'btn btn-primary', 'id' => 'uploadButton'])
            ?>
        </div>

        <?php ActiveForm::end() ?>

    </div>

</div>