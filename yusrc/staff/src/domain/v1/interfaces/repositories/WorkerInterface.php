<?php

namespace yubundle\staff\domain\v1\interfaces\repositories;

use yii2rails\domain\interfaces\repositories\CrudInterface;

/**
 * Interface WorkerInterface
 * 
 * @package yubundle\staff\domain\v1\interfaces\repositories
 * 
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 */
interface WorkerInterface extends CrudInterface {

    public function allByPersonIds(array $personIds);
    public function allByPostIds(array $postIds);

}
