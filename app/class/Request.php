<?php

namespace app\class;

use app\util\Validate;
use support\Request as SupportRequest;

class Request extends SupportRequest
{
    public function validate(array $rules)
    {
        return Validate::Data($this->post(), $rules);
    }
}
