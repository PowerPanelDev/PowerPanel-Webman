<?php

namespace app\class;

use app\model\ApiKey;
use app\util\Validate;
use support\Request as SupportRequest;

class Request extends SupportRequest
{
    public ApiKey $apiKey;

    public function validate(array $rules)
    {
        return Validate::Data($this->post(), $rules);
    }
}
