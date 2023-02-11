<?php

namespace app\handler\Instance;

use app\client\NodeClient;
use app\model\Instance;

class Handler
{
    protected Instance $instance;
    protected NodeClient $client;

    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
        $this->setClient($instance->getClient());
    }

    protected function setClient(NodeClient $client)
    {
        $this->client = $client;
    }
}
