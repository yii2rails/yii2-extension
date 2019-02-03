<?php

namespace yii2rails\extension\package\domain\helpers;

use yii2rails\extension\yii\helpers\FileHelper;

class ConfigRepositoryHelper  {
	
	public static function idToDir($id) {
		$dir = FileHelper::getAlias('@vendor/' . $id);
		return $dir;
	}
	
}
