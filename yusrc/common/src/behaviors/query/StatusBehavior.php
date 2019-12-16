<?php

namespace yubundle\common\behaviors\query;

use yii2rails\domain\behaviors\query\BaseQueryFilter;
use yii2rails\domain\data\Query;

class StatusBehavior extends BaseQueryFilter {

    public $value = null;
    public $isRewritable = false;
    public $isEnable = true;

    public function prepareQuery(Query $query) {
        if(!$this->isEnable) {
            return;
        }
        $value = $query->getWhere('status');
        if(!$this->isRewritable || $value === null) {
            $query->andWhere(['status' => $this->value]);
        }
    }

}
