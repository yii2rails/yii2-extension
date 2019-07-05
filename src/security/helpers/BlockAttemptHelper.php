<?php

namespace yii2rails\extension\security\helpers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\services\base\BaseService;
use yii2rails\domain\traits\MethodEventTrait;
use yii2rails\extension\common\enums\StatusEnum;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\security\entities\SecurityConfigEntity;
use yii2rails\extension\security\exceptions\BannedException;
use yii2rails\extension\security\exceptions\BannedLeftException;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2rails\extension\yii\helpers\ArrayHelper;
use yubundle\account\domain\v2\behaviors\UserActivityFilter;
use yubundle\account\domain\v2\enums\AccountEventEnum;
use yubundle\account\domain\v2\events\AccountAuthenticationEvent;
use yubundle\account\domain\v2\filters\token\BaseTokenFilter;
use yubundle\account\domain\v2\filters\token\DefaultFilter;
use yubundle\account\domain\v2\forms\LoginForm;
use yubundle\account\domain\v2\helpers\AuthHelper;
use yubundle\account\domain\v2\helpers\TokenHelper;
use yubundle\account\domain\v2\interfaces\services\AuthInterface;
use yii\web\ServerErrorHttpException;
use yubundle\account\domain\v2\entities\LoginEntity;

class BlockAttemptHelper {

    public static function check(string $cacheKey, SecurityConfigEntity $configEntity) {
        $attemptCacheKey = $cacheKey . '_attempt';
        //$banCacheKey = $cacheKey . '_ban';

        $isBanned = self::isBanned($cacheKey, $configEntity);
        //
        if($isBanned) {
            $time = TIMESTAMP - $isBanned;
            $tileLeft = $configEntity->block_expire - $time;
            throw new BannedException($tileLeft);
        }

        $attempts = Yii::$app->cache->get($attemptCacheKey);
        $attempts = $attempts == null ? 0 : $attempts;
    }

    public static function increment(string $cacheKey, SecurityConfigEntity $configEntity) {
        $attemptCacheKey = $cacheKey . '_attempt';
        //$banCacheKey = $cacheKey . '_ban';

        $attempts = Yii::$app->cache->get($attemptCacheKey);
        $attempts = $attempts ?: 0;
        $attempts++;
        Yii::$app->cache->set($attemptCacheKey, $attempts, $configEntity->attempt_expire);
        if ($attempts >= $configEntity->attempt_count) {
            self::setBan($cacheKey, $configEntity);
            throw new BannedException($configEntity->block_expire);
        }
        //self::check($cacheKey, $configEntity);
        return $configEntity->attempt_count - $attempts;
    }

    protected static function setBan(string $cacheKey, SecurityConfigEntity $configEntity) {
        $attemptCacheKey = $cacheKey . '_attempt';
        $banCacheKey = $cacheKey . '_ban';
        Yii::$app->cache->set($banCacheKey, TIMESTAMP, $configEntity->block_expire);
        Yii::$app->cache->delete($attemptCacheKey);
    }

    protected static function isBanned(string $cacheKey, SecurityConfigEntity $configEntity) {
        $attemptCacheKey = $cacheKey . '_attempt';
        $banCacheKey = $cacheKey . '_ban';
        $timestampExpire = Yii::$app->cache->get($banCacheKey);
        return $timestampExpire;
    }

}
