<?php

namespace yii2rails\extension\common\enums;

use yii2rails\extension\enum\base\BaseEnum;

class StatusEnum extends BaseEnum {

    // откоючен / премодерация
    const DISABLE = 0;

    // включен / одобрен
    const ENABLE = 1;

    // отвергнут
    const REJECTED = 2;

}
