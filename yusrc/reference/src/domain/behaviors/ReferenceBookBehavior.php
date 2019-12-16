<?php

namespace yubundle\reference\domain\behaviors;

use yii2rails\domain\behaviors\query\BaseQueryFilter;
use yii2rails\domain\data\Query;

class ReferenceBookBehavior extends BaseQueryFilter {

    public $referenceBookId = 1;

    public function prepareQuery(Query $query) {
        $query->andWhere(['reference_book_id' => $this->referenceBookId]);
    }

}
