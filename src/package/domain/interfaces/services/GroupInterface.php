<?php

namespace yii2lab\extension\package\domain\interfaces\services;

use yii2lab\domain\interfaces\services\CrudInterface;
use yii2lab\domain\data\Query;

/**
 * Interface GroupInterface
 * 
 * @package yii2lab\extension\package\domain\interfaces\services
 * 
 * @property-read \yii2lab\extension\package\domain\Domain $domain
 * @property-read \yii2lab\extension\package\domain\interfaces\repositories\GroupInterface $repository
 */
interface GroupInterface extends CrudInterface {

    public function allNames(Query $query = null);

}
