<?php

namespace yubundle\staff\domain\v1\interfaces\services;

use yubundle\staff\domain\v1\entities\WorkerEntity;
use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface WorkerInterface
 *
 * @package yubundle\staff\domain\v1\interfaces\services
 *
 * @property-read \yubundle\staff\domain\v1\Domain $domain
 * @property-read \yubundle\staff\domain\v1\interfaces\repositories\WorkerInterface $repository
 */
interface WorkerInterface extends CrudInterface
{

    public function oneSelf(): WorkerEntity;
    public function allByPersonIds(array $personIds);
    public function allByPostIds(array $personIds);

}
