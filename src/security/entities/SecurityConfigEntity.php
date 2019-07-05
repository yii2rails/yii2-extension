<?php

namespace yii2rails\extension\security\entities;

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

/**
 * Class SecurityConfigEntity
 * @package yii2rails\extension\security\entities
 *
 * @property $attempt_count
 * @property $attempt_expire
 * @property $block_expire
 */
class SecurityConfigEntity extends BaseEntity {

    protected $attempt_count;
    protected $attempt_expire;
    protected $block_expire;
    //protected $;
    //protected $;

    public function loadConfig($config) {
        $this->attempt_count = $config['attemptCount'];
        $this->attempt_expire = $config['attemptExpire'];
        $this->block_expire = $config['blockExpire'];
    }
}
