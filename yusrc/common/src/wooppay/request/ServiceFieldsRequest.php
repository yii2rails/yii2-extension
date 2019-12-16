<?php

namespace yubundle\common\wooppay\request;

/**
 * Class ServiceFieldsRequest
 * @package yubundle\common\wooppay\request
 *
 * @property-write $fields
 */
class ServiceFieldsRequest extends Request
{

    public $service;
    public $serviceId;
    public $merchant = null;
    private $fields = null;

    public function rules()
    {
        return [];
    }

    public function setFields($fields)
    {
        $this->fields = json_encode($fields);
    }

}
