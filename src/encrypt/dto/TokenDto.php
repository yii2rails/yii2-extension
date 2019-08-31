<?php

namespace yii2rails\extension\encrypt\dto;

use yii2rails\domain\base\BaseDto;

class TokenDto extends BaseDto {

    public $header_encoded = null;
    public $payload_encoded = null;
    public $signature_encoded = null;

    public $header = null;
    public $payload = null;
    public $signature = null;

}
