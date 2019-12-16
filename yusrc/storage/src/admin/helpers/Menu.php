<?php

namespace yubundle\storage\admin\helpers;

use yubundle\storage\domain\v1\enums\StoragePermissionEnum;
use yii2rails\extension\menu\interfaces\MenuInterface;

class Menu implements MenuInterface {

	public function toArray() {
		return [
            'label' => ['storage/storage', 'title'],
            'url' => 'storage/storage',
            'icon' => 'file-text-o',
            'module' => 'storage',
            'access' => StoragePermissionEnum::MANAGE,
		];
	}

}
