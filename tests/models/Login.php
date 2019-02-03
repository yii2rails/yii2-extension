<?php
namespace yii2rails\extension\tests\models;

use yii2rails\extension\store\ActiveStore;

class Login extends ActiveStore
{
	public static $dir = '@vendor/yii2rails/yii2-extension/tests/store/data';
	public static $name = 'login';
	
}
