<?php

namespace yubundle\account\web\helpers;

use Yii;
use yii\web\NotFoundHttpException;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\data\Query;
use yii2rails\extension\menu\helpers\MenuHelper;
use yii2rails\extension\menu\interfaces\MenuInterface;
use yii2rails\extension\yii\helpers\Html;
use yubundle\account\domain\v2\helpers\LoginHelper;
use yii2module\profile\domain\v2\entities\PersonEntity;
use yii2module\profile\widget\Avatar;
use yubundle\money\domain\entities\BalanceEntity;
use yubundle\money\domain\entities\ServiceEntity;
use yubundle\money\web\helpers\ViewHelper;
use yubundle\user\domain\v1\entities\ClientEntity;

class Menu implements MenuInterface {
	
	public function toArray() {
		return self::menu(null);
	}
	
	public static function menu($items) {
	    return self::getItems($items);
		return $menu = [
			'label' => self::getLabel(),
			'module' => 'user',
			'encode' => false,
			'items' => self::getItems($items),
		];
	}
	
	public static function getItems($items = null) {
		if(!empty($items)) {
			return $items;
		}
		if(Yii::$app->user->isGuest) {
			return self::getGuestMenu();
		} else {
			return self::getUserMenu();
		}
	}
	
	private static function getLabel() {
		if(Yii::$app->user->isGuest) {
			return Html::fa('user') . NBSP . Yii::t('account/auth', 'title');
		} else {
            return Html::img(EnvService::getStaticUrl('images/avatar/user.png'), [
                'style' => 'width: 18px;',
            ]);
		}
	}
	
	public static function getUseName() {
		$title = null;
		if(\App::$domain->has('profile')) {
			/** @var PersonEntity $personEntity */
			$personEntity = \App::$domain->profile->person->getSelf();
			$title = $personEntity->title;
		}
		if(!$title) {
			$title = \App::$domain->account->auth->identity->login;
			/*if(LoginHelper::validate($title)) {
				$title = LoginHelper::format($title);
			}*/
		}
		return $title;
	}
	
	private static function getGuestMenu()
	{
		return [
            [
                'label' => ['account/registration', 'title'],
                'url' => 'user/registration',
            ],
			[
				'label' => ['account/auth', 'login_action'],
				'url' => Yii::$app->user->loginUrl,
			],
			/*[
				'label' => ['account/restore-password', 'title'],
				'url' => 'user/restore-password',
			],*/
		];
	}
	
	private static function getBalance() {
		$balanceStr = '';
		if(\App::$domain->has('money')) {
			/** @var BalanceEntity[] $balanceCollection */
            $query = new Query;
            $query->with('currency');
            $balanceCollection = \App::$domain->money->balance->allBySelf($query);
			foreach ($balanceCollection as $balanceEntity) {
				if($balanceEntity->active) {
					$amountHtml = ViewHelper::getTransactionAmountHtml($balanceEntity->active, $balanceEntity->currency->char);
					$balanceStr .= $amountHtml . ' | ';
				}
			}
			$balanceStr = trim($balanceStr, ' |');
		}
		return $balanceStr;
	}
	
	private static function getWallet() {
		$walletHost = EnvService::get('wallet.host');
		if(empty($walletHost)) {
			$walletHost = EnvService::getUrl(FRONTEND);
		}
		return $walletHost;
	}
	
	private static function getUserMenu()
	{
		
		
		$items = [
			'yii2module\profile\module\v1\helpers\Menu',
			/*[
				'label' => self::getUseName(),
			],*/
		];
		
		if(\App::$domain->has('money')) {
			$walletHost = self::getWallet();
			$linkOptions = $walletHost == EnvService::getUrl(FRONTEND) ? [] : ['target' => '_blank'];
			$items[] = [
				'label' => 'Баланс: ' . self::getBalance(),
				'encode' => false,
			];
			$items[] = MenuHelper::DIVIDER;
			$items[] = [
				'label' => ['money/transaction', 'title'],
				'url' => $walletHost . '/money/transaction',
				'linkOptions' => $linkOptions,
			];
			$items[] = [
				'label' => ['money/service', 'title'],
				'url' => $walletHost . '/money/service',
				'linkOptions' => $linkOptions,
			];
            $items[] = [
                'label' => ['money/balance', 'title'],
                'url' => $walletHost . '/money/balance',
                'linkOptions' => $linkOptions,
            ];
		}
		
		$items[] = [
			MenuHelper::DIVIDER,
			[
				'label' => ['account/auth', 'logout_action'],
				'url' => 'user/auth/logout',
				'linkOptions' => ['data-method' => 'post'],
			],
		];
		
        return [
            'label' => self::getLabel(),
            'module' => 'user',
            'encode' => false,
            'items' => $items,
        ];
	}

}
