<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii2rails\extension\web\helpers\Page;
use yii2bundle\navigation\domain\widgets\Breadcrumbs;
use yii2lab\applicationTemplate\frontend\assets\AppAsset;
use yii2bundle\navigation\domain\widgets\Alert;

AppAsset::register($this);
?>

<?php Page::beginDraw() ?>

<div class="wrap">
    <header class="main-header">
		<?= Page::snippet('navbar', '@yubundle/common/project/frontend', ['isFixedTop' => true]) ?>
    </header>
    <div class="container">
		<?= Breadcrumbs::widget() ?>
		<?= Alert::widget() ?>
		<?= $content ?>
    </div>
</div>

<div class="page-footer">
    <div class="container">
		<?= Page::snippet('footer', '@yii2lab/applicationTemplate/common') ?>
    </div>
</div>

<?php Page::endDraw() ?>
