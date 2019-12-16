<?php

namespace yubundle\user\domain\v1\interfaces\services;
use yubundle\user\domain\v1\entities\AddressEntity;

/**
 * Interface AddressInterface
 * 
 * @package yubundle\user\domain\v1\interfaces\services
 * 
 * @property-read \yubundle\user\domain\v1\Domain $domain
 * @property-read \yubundle\user\domain\v1\interfaces\repositories\AddressInterface $repository
 */
interface AddressInterface {

    public function myAddress() : AddressEntity;
    public function oneByEmail(string $email) : AddressEntity;
    public function parseEmail(string $email): AddressEntity;
    public function isInternal(string $email): bool;

}
