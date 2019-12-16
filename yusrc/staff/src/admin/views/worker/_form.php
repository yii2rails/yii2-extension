<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii2rails\extension\yii\helpers\ArrayHelper;
use yii2rails\domain\data\Query;

$actionName = Yii::$app->controller->action->id;
$companyId = \App::$domain->account->auth->identity->company_id;

$this->title = Yii::t('staff/worker', 'new_worker');

if ($actionName == "update") {
    $this->title = Yii::t('staff/worker', 'edit_worker');
}

\App::$domain->navigation->breadcrumbs->create($this->title);

$bookQuery = new Query;
$bookQuery->andWhere(['entity' => 'sex']);
/* @var $sexBookEntity \yubundle\reference\domain\entities\BookEntity */
$sexBookEntity = App::$domain->reference->book->repository->one($bookQuery);

$itemQuery = new Query;
$itemQuery->andWhere(['reference_book_id' => $sexBookEntity->id]);

$postQuery = new Query;
$postQuery->andWhere(['entity' => 'posts_' . $companyId]);
$bookEntity = App::$domain->reference->book->repository->one($postQuery);

$postQuery = new Query;
$postQuery->andWhere(['reference_book_id' => $bookEntity->id]);

$divisionQuery = new Query;
$divisionQuery->andWhere(['company_id' => $companyId]);

?>
<div class="send-email">

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'last_name')->textInput() ?>
            <?= $form->field($model, 'middle_name')->textInput() ?>
            <?= $form->field($model, 'birth_date')->textInput() ?>
            <?= $form->field($model, 'code')->textInput() ?>
            <?= $form->field($model, 'sex')->radioList(ArrayHelper::map(App::$domain->reference->item->all($itemQuery), 'id', 'value'))?>
            <?= $form->field($model, 'outer_phone')->textInput(['readonly'=> $actionName == "create" ? false : true ]) ?>

            <?= $form->field($model, 'division')->dropDownList(ArrayHelper::map(App::$domain->staff->division->all($divisionQuery), 'id', 'name'))?>
            <?= $form->field($model, 'post')->dropDownList(ArrayHelper::map(App::$domain->reference->item->all($postQuery), 'id', 'value'))?>
            <?= $form->field($model, 'office')->textInput() ?>
            <?= $form->field($model, 'private_phone')->textInput() ?>

            <?= $form->field($model, 'user_login')->textInput(['readonly'=> $actionName == "create" ? false : true ]) ?>
            <?= $form->field($model, 'user_password')->textInput(['type' => 'password']) ?>
            <?= $form->field($model, 'accept_password')->textInput(['type' => 'password']) ?>
            <div class="form-group">

                <?php
                if ($actionName == "create") {
                    echo Html::submitButton(Yii::t('action', 'create'), ['class' => 'btn btn-primary']);
                } else if ($actionName == "update") {
                    echo Html::submitButton(Yii::t('action', 'save'), ['class' => 'btn btn-primary']);
                }

                ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
