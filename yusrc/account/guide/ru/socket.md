Socket
===

## Настройка

В конфиге `env-local`:

```php
$config = [
	'servers' => [
        'socket' => [
            'tcpHost' => 'tcp://127.0.0.1:1234',
            'wsHost' => 'websocket://0.0.0.0:8000',
        ],
	],
];
```

## Комадны socket-сервера

* `php socket start`
* `php socket start -d`
* `php socket status`
* `php socket stop`
* `php socket restart`
* `php socket restart -d`
* `php socket reload`

Опция `-d` - демонизировать скрипт.

Консольный скрипт находится тут:

	vendor\yubundle\yii2-account\bin

## Соединение с web-socket

Создаем `index.html` с содержимым:

```html
<html>
<head>
    <script>
        ws = new WebSocket("ws://127.0.0.1:8000/?login=tester1&password=Wwwqqq111&evetns=NEW_MESSAGE,UPDATE_BALANCE");
        ws.onmessage = function(evt) {
			var data = JSON.parse(evt.data);
			alert(data.content);
		};
    </script>
</head>
</html>
```

Для аутентификации пользователя есть два варианта:

* по логину (передаем параметры: `login`, `password`)
* по токену (передаем параметр `authorization`)

Также, необходимо указывать события, на которые хотим подписаться, для этого передаем параметр `events`, указываем имена событий через запятую.

## Отправка события пользователю

```php
$event = new SocketEventEntity;
$event->name = 'NEW_EMAIL';
$event->user_id = $personId;
$event->data = $mailEntity->toArray();
try {
    App::$domain->account->socket->sendMessage($event);
} catch (ErrorException $e) {}
```
