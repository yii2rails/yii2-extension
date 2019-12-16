<?php

namespace yubundle\common\project\common\locators;

use yii2rails\domain\base\BaseDomainLocator;

/**
 * @property-read \yubundle\user\domain\v1\Domain $user
 * @property-read \yubundle\account\domain\v2\Domain $account
 * @property-read \yubundle\storage\domain\v1\Domain $storage
 * @property-read \yubundle\reference\domain\Domain $reference
 * @property-read \yubundle\staff\domain\v1\Domain $staff
 * @property-read \yubundle\common\partner\Domain $partner
 */
class DomainLocator extends BaseDomainLocator {}
