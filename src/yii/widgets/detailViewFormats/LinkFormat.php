<?php

namespace yii2rails\extension\yii\widgets\detailViewFormats;

use yii2rails\extension\arrayTools\base\BaseCollection;
use yii2rails\extension\widget\helpers\WidgetHelper;
use yii2rails\extension\yii\helpers\ArrayHelper;
use yii2rails\extension\yii\helpers\Html;

class LinkFormat {
	
	public $baseUrl = '';
	public $titleAttribute = 'title';
	public $idAttribute = 'id';
	public $urlTemplate = '/{base}?id={id}';
	
	public function run($value) {
		if($value instanceof BaseCollection || is_array($value)) {
			$result = [];
			foreach($value as $item) {
				$result[] = $this->renderItem($item);
			}
			return Html::ulRaw($result);
		} else {
			return $this->renderItem($value);
		}
	}

	private function renderItem($value) {
		$title = ArrayHelper::getValue($value, $this->titleAttribute);
		$id = ArrayHelper::getValue($value, $this->idAttribute);
		$url = WidgetHelper::renderTemplate($this->urlTemplate, [
			'base' => $this->baseUrl,
			'id' => $id,
		]);
		return $value ? Html::a($title, [$url]) : null;
	}
	
}
