<?php

namespace yii2rails\extension\markdown\widgets;

use yii\apidoc\templates\bootstrap\assets\AssetBundle;
use yii\base\Widget;
use yii2rails\extension\scenario\collections\ScenarioCollection;
use yii2rails\extension\scenario\helpers\ScenarioHelper;
use yii2rails\extension\markdown\widgets\helpers\MarkdownHelper;

class Markdown extends Widget {

	public $content;
	public $filters = [
		'yii2rails\extension\markdown\widgets\filters\AlertFilter',
		'yii2rails\extension\markdown\widgets\filters\CodeFilter',
		'yii2rails\extension\markdown\widgets\filters\ImgFilter',
		'yii2rails\extension\markdown\widgets\filters\LinkFilter',
		'yii2rails\extension\markdown\widgets\filters\MarkFilter',
		//'yii2rails\extension\markdown\widgets\filters\HeaderFilter',
	];

	public function init() {
		parent::init();
		$this->registerAssets();
	}
	
	/**
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function run() {
		$html = MarkdownHelper::toHtml($this->content);
		$filterCollection = new ScenarioCollection($this->filters);
		return $filterCollection->runAll($html);
	}

	protected function registerAssets() {
		$view = $this->getView();
		AssetBundle::register($view);
	}

}