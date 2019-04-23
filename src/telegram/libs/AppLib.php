<?php

namespace yii2rails\extension\telegram\libs;

use yii2rails\extension\telegram\entities\UserEntity;
use yii2rails\extension\telegram\helpers\AppHelper;
use yii2rails\extension\telegram\interfaces\repositories\ResponseInterface;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;
use yii\web\NotFoundHttpException;
use yii2rails\extension\common\helpers\ClassHelper;

class AppLib {
	
	const CACHE_KEY = 'telegram_bot_scenario_2';

    /**
     * @var Client
     */
	public $bot;
	public $botId;
	public $handlers;
	public $request;

    /**
     * @var UserEntity
     */
    public $userEntity;

    /**
     * @var ResponseInterface
     */
    public $response;
	private $isHandled = false;
	
	public function __construct($botToken) {
		$botEntity = AppHelper::forgeBotEntityFromToken($botToken);
        $this->botId = $botEntity->id;
		$this->bot = new Client($botEntity->token);
        $req = file_get_contents('php://input');
        $this->request = \GuzzleHttp\json_decode($req);
        $this->userEntity = \App::$domain->telegram->user->oneByFrom($this->request->message->from, $this->botId);

		//$this->response = new Response($this);
        \App::$domain->telegram->response->repository->setApp($this);
        \App::$domain->telegram->response->repository->columns = 4;
		$this->response = \App::$domain->telegram->response->repository;
        try {
            \App::$domain->telegram->bot->oneById($botEntity->id);
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Bot not found!', 0, $e);
        }
	}

	public function setRoutes($handlers) {
		$this->handlers = $handlers;
		// Отлов любых сообщений + обработка reply-кнопок
		$this->bot->on($this->updateHandler(), function(Update $message) {
			return true; // когда тут true - команда проходит
		});
	}

    public function run() {
        $this->bot->run();
    }

    public function setSession($key, $val) {
	    $session = $this->getSession();
        $session[$key] = $val;
        $this->userEntity->session = json_encode($session);
        \App::$domain->telegram->user->update($this->userEntity);
    }

    public function getSession($key = null) {
        $session = (array) json_decode($this->userEntity->session);
	    if($key) {
            $session = $session[$key];
        }
        return $session;
    }

    public function clear() {
        $this->userEntity->session = [];
        \App::$domain->telegram->user->update($this->userEntity);
    }

    public function setState($state) {
	    $this->userEntity->state = $state;
        \App::$domain->telegram->user->update($this->userEntity);
	}
	
	public function getState() {
	    return $this->userEntity->state;
	}
	
	public function clearState() {
		$this->setState('default');
	}
	
	public function isHandled() {
		return $this->isHandled;
	}
	
	public function updateHandler() {
		return function(Update $update) {
			$message = $update->getMessage();
			$this->handleMessage($message);
		};
	}
	
	public function handleMessage(Message $message) {
		$this->isHandled = false;
		foreach($this->handlers as $pattern => $handler) {
			$h = ClassHelper::createObject($handler, [$this]);
			$h->name = $pattern;
			if($h->isMatch($message)) {
				$h->run($message);
				$this->isHandled = true;
				return;
			}
		}
	}

}
