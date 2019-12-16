<?php

namespace yubundle\reference\admin\helpers;

use yii2rails\extension\menu\interfaces\MenuInterface;
use yii2bundle\lang\domain\enums\LangPermissionEnum;

class Menu implements MenuInterface {
	
	public function toArray() {
		return [
			'label' => ['reference/main', 'title'],
			'url' => 'reference/book',
			/*'module' => 'lang',
			//'icon' => 'language',
			'access' => LangPermissionEnum::MANAGE,*/
		];
	}

}
