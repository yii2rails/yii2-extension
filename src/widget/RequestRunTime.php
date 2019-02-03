<?php

namespace yii2rails\extension\widget;

use yii\base\Widget;
use yii2rails\extension\develop\helpers\Debug;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\yii\helpers\Html;

class RequestRunTime extends Widget {
	
	public $precision = 2;
	
	public function run() {
		$runtime = Debug::getRuntime(TimeEnum::SECOND_PER_SECOND, $this->precision);
		$labelSecond = $runtime . ' s';
		$labelMillisecond = Debug::getRuntime(TimeEnum::SECOND_PER_MILLISECOND, 0) . ' ms';
		$hint = 'runtime: ' . $labelSecond . ' (' . $labelMillisecond . ')';
		echo Html::tag('span', $labelSecond, [
			'title' => $hint,
		]);
	}
	
}