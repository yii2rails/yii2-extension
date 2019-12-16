Установка
===

Устанавливаем зависимость:

```
composer require yubundle/yii2-account
```

Объявляем API модуль:

```php
return [
	'modules' => [
		// ...
		'account' => [
			'class' => 'yubundle\account\api\Module',
		],
		// ...
	],
];
```

Объявляем frontend модуль:

```php
return [
	'modules' => [
		// ...
		'user' => [
			'class' => 'yubundle\account\module\Module',
		],
		// ...
	],
];
```

Объявляем backend модуль:

```php
return [
	'modules' => [
		// ...
		'user' => 'yubundle\account\web\BackendModule',
		// ...
	],
];
```

Объявляем console модуль:

```php
return [
	'modules' => [
		// ...
		'user' => 'yubundle\account\web\BackendModule',
		// ...
	],
];
```

Объявляем домен:

```php
return [
		// ...
		'account' => 'yubundle\account\domain\v2\Domain',
		// ...
	],
];
```
