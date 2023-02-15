<?php

namespace app\class;

use app\model\ApiKey;
use app\model\Instance;
use app\util\Validate;
use support\Request as SupportRequest;

class Request extends SupportRequest
{
    public ApiKey $apiKey;
    public Instance $instance;

    public function validate(array $rules)
    {
        return Validate::Data($this->post(), $rules);
    }

    public function getAK()
    {
        return $this->apiKey;
    }

    public function getUser()
    {
        return $this->apiKey->user;
    }

    public function getInstance()
    {
        return $this->instance;
    }
}
